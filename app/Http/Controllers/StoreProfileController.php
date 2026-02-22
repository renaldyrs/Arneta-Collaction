<?php

namespace App\Http\Controllers;

use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StoreProfileController extends Controller
{
    // Disk yang digunakan untuk penyimpanan lokal
    protected $disk = 'public'; // Menggunakan disk public untuk penyimpanan lokal

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
                $profile->logo = $path; // Ini akan menyimpan path relatif (contoh: store-profile/2025/02/logo_1234567890.jpg)
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
            'phone' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $profile = StoreProfile::firstOrFail();
            $oldLogoPath = $profile->logo; // Ini path relatif

            $profile->name = $request->name;
            $profile->address = $request->address;
            $profile->phone = $request->phone;

            if ($request->hasFile('logo')) {
                $newLogoPath = $this->uploadLogo($request->file('logo'));
                if ($newLogoPath) {
                    $profile->logo = $newLogoPath; // Simpan path relatif baru
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
     * Upload logo ke storage lokal (public disk)
     */
    private function uploadLogo($file)
    {
        try {
            // Generate nama file unik
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();

            // Path penyimpanan dengan struktur folder
            $path = 'store-profile/' . date('Y/m') . '/' . $filename;

            // Upload file ke storage lokal (public disk)
            // Ini akan menyimpan di storage/app/public/store-profile/...
            Storage::disk('public')->put($path, file_get_contents($file));

            // Kembalikan path relatif untuk disimpan di database
            // Path ini akan digunakan dengan asset('storage/' . $path)
            return $path;

        } catch (\Exception $e) {
            Log::error('Logo upload failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hapus logo lama dari storage lokal
     */
    private function deleteOldLogo($logoPath)
    {
        if (empty($logoPath)) {
            return false;
        }

        try {
            // Hapus file dari storage lokal jika ada
            if (Storage::disk('public')->exists($logoPath)) {
                return Storage::disk('public')->delete($logoPath);
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to delete old logo: ' . $e->getMessage());
            return false;
        }
    }
}