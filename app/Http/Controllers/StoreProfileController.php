<?php

namespace App\Http\Controllers;

use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StoreProfileController extends Controller
{
    // Disk yang digunakan untuk penyimpanan
    protected $disk = 'laravelcloud';

    public function index()
    {
        $profile = StoreProfile::first();
        
        if (!$profile) {
            return redirect()->route('store-profile.create')
                ->with('error', 'Profile toko belum ada. Silakan buat profile toko terlebih dahulu.');
        }

        return view('store-profile.index', compact('profile'));
    }

    public function create()
    {
        if (StoreProfile::exists()) {
            return redirect()->route('store-profile.edit')
                ->with('info', 'Profile toko sudah ada. Anda dapat mengeditnya di sini.');
        }

        return view('store-profile.create');
    }

    public function edit()
    {
        $profile = StoreProfile::firstOrFail();
        return view('store-profile.edit', compact('profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
                $path = $this->uploadLogo($request->file('logo'));
                $profile->logo = $path;
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

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();

        // Path penyimpanan
        $path = 'store-profile/' . date('Y/m') . '/' . $filename;

        // Upload file ke S3
        Storage::disk($this->disk)->put(
    $path, 
    file_get_contents($file),
    ['visibility' => 'public', 'ACL' => 'public-read']
);


        // Ambil full URL dari S3
        $url = Storage::disk($this->disk)->url($path);

        // Force URL pakai HTTPS (biar aman untuk <img src>)
        return str_replace('http://', 'https://', $url);

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
        try {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $baseUrl = rtrim(Storage::disk($this->disk)->url(''), '/');
                $parsedUrl = parse_url($url);
                $parsedBase = parse_url($baseUrl);

                // Memastikan host dan path awal sama
                if (isset($parsedUrl['host'], $parsedBase['host']) &&
                    $parsedUrl['host'] === $parsedBase['host'] &&
                    strpos($parsedUrl['path'], $parsedBase['path']) === 0) {
                    return ltrim(substr($parsedUrl['path'], strlen($parsedBase['path'])), '/');
                }
            }
        } catch (\Exception $e) {
            Log::error('Path extraction failed: ' . $e->getMessage());
        }

        return null;
    }
}
