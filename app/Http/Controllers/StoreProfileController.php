<?php
namespace App\Http\Controllers;

use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StoreProfileController extends Controller
{
    // Disk yang digunakan untuk penyimpanan
    protected $disk = 'laravelcloud';

    // Direktori untuk menyimpan logo
    protected $logoDirectory = 'store-profile/logos';

    // Maksimal ukuran file logo (dalam KB)
    protected $maxLogoSize = 2048;

    /**
     * Menampilkan profil toko
     */
    public function index()
    {
        try {
            $profile = StoreProfile::first();
            
            if (!$profile) {
                return redirect()->route('store-profile.create')
                    ->with('error', 'Profile toko belum ada. Silakan buat profile toko terlebih dahulu.');
            }

            return view('store-profile.index', [
                'profile' => $profile,
                'logoUrl' => $this->getVerifiedLogoUrl($profile->logo)
            ]);
                
        } catch (\Exception $e) {
            Log::error('StoreProfile index error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat profil toko.');
        }
    }

    /**
     * Form create profil toko
     */
    public function create()
    {
        if (StoreProfile::exists()) {
            return redirect()->route('store-profile.edit')
                ->with('info', 'Profile toko sudah ada. Anda dapat mengeditnya di sini.');
        }

        return view('store-profile.create');
    }

    /**
     * Form edit profil toko
     */
    public function edit()
    {
        try {
            $profile = StoreProfile::firstOrFail();
            return view('store-profile.edit', [
                'profile' => $profile,
                'logoUrl' => $this->getVerifiedLogoUrl($profile->logo)
            ]);
                
        } catch (\Exception $e) {
            Log::error('StoreProfile edit error: ' . $e->getMessage());
            return redirect()->route('store-profile.index')
                ->with('error', 'Profil toko tidak ditemukan.');
        }
    }

    /**
     * Menyimpan profil toko baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:'.$this->maxLogoSize,
                'dimensions:max_width=1000,max_height=1000'
            ],
        ]);

        if (StoreProfile::exists()) {
            return redirect()->route('store-profile.edit')
                ->with('error', 'Profile toko sudah ada. Silakan edit profile yang sudah ada.');
        }

        try {
            $profile = new StoreProfile();
            $profile->name = $request->name;
            $profile->address = $request->address;
            $profile->phone = $request->phone;

            if ($request->hasFile('logo')) {
                $logoPath = $this->uploadLogo($request->file('logo'));
                if ($logoPath) {
                    $profile->logo = $logoPath;
                }
            }

            $profile->save();

            return redirect()->route('store-profile.index')
                ->with('success', 'Profile toko berhasil dibuat.');
                
        } catch (\Exception $e) {
            Log::error('Store profile creation error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal membuat profile toko. Silakan coba lagi.');
        }
    }

    /**
     * Memperbarui profil toko
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:'.$this->maxLogoSize,
                'dimensions:max_width=1000,max_height=1000'
            ],
        ]);

        try {
            $profile = StoreProfile::firstOrFail();
            $oldLogoPath = $profile->logo;

            $profile->name = $request->name;
            $profile->address = $request->address;
            $profile->phone = $request->phone;

            if ($request->hasFile('logo')) {
                $newLogoPath = $this->uploadLogo($request->file('logo'));
                if ($newLogoPath) {
                    $profile->logo = $newLogoPath;
                    $this->deleteOldLogo($oldLogoPath);
                }
            }

            $profile->save();

            return redirect()->route('store-profile.index')
                ->with('success', 'Profile toko berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Store profile update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profile toko: ' . $e->getMessage());
        }
    }

    /**
     * Upload logo ke cloud storage
     */
    private function uploadLogo($file)
    {
        try {
            // Generate nama file unik
            $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            
            // Path penyimpanan
            $path = $this->logoDirectory.'/'.date('Y/m').'/'.$filename;
            
            // Upload ke storage menggunakan stream
            Storage::disk($this->disk)->put($path, fopen($file->getRealPath(), 'r+'));
            
            // Set visibility file
            Storage::disk($this->disk)->setVisibility($path, 'public');
            
            // Verifikasi upload berhasil
            if (!Storage::disk($this->disk)->exists($path)) {
                throw new \Exception('File upload verification failed');
            }
            
            // Return full URL
            return Storage::disk($this->disk)->url($path);
            
        } catch (\Exception $e) {
            Log::error('Logo upload failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hapus logo lama dari storage
     */
    private function deleteOldLogo($logoUrl)
    {
        if (empty($logoUrl)) {
            return false;
        }

        try {
            $path = $this->extractPathFromUrl($logoUrl);
            
            if ($path && Storage::disk($this->disk)->exists($path)) {
                // Jangan hapus jika ini adalah logo default
                if (Str::contains($path, 'default-logo')) {
                    return false;
                }
                
                return Storage::disk($this->disk)->delete($path);
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to delete old logo: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ekstrak path dari URL
     */
    private function extractPathFromUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $baseUrl = rtrim(Storage::disk($this->disk)->url(''), '/');
            $url = rtrim($url, '/');
            
            if (Str::startsWith($url, $baseUrl)) {
                return ltrim(Str::replaceFirst($baseUrl, '', $url), '/');
            }
        }
        
        return $url;
    }

    /**
     * Dapatkan URL logo yang sudah diverifikasi
     */
    private function getVerifiedLogoUrl($logoPath)
    {
        $defaultLogo = asset('images/default-logo.png');
        
        if (empty($logoPath)) {
            return $defaultLogo;
        }

        try {
            // Jika sudah URL lengkap
            if (filter_var($logoPath, FILTER_VALIDATE_URL)) {
                return $logoPath;
            }
            
            // Jika path storage
            $path = $this->extractPathFromUrl($logoPath);
            
            if (Storage::disk($this->disk)->exists($path)) {
                return Storage::disk($this->disk)->url($path);
            }
            
            return $defaultLogo;
        } catch (\Exception $e) {
            Log::error('Logo URL verification error: ' . $e->getMessage());
            return $defaultLogo;
        }
    }
}