<?php
namespace App\Http\Controllers;

use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreProfileController extends Controller
{
    public function index(){
        $storeProfile = StoreProfile::first();
        if (!$storeProfile) {
            return redirect()->route('store-profile.create')->with('error', 'Profile toko belum ada. Silakan buat profile toko terlebih dahulu.');
        }
        $profile = StoreProfile::first();
        return view('store-profile.index', compact('profile'));
    }

    public function create(){
        return view('store-profile.create');
    }

    public function edit()
    {
        $profile = StoreProfile::first();
        return view('store-profile.edit', compact('profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = StoreProfile::first();
        if ($profile) {
            return redirect()->route('store-profile.edit')->with('error', 'Profile toko sudah ada.');
        }

        $profile = new StoreProfile();
        $profile->name = $request->name;
        $profile->address = $request->address;
        $profile->phone = $request->phone;

        if ($request->hasFile('logo')) {
            // Simpan file ke bucket Laravel Cloud
            $logoPath = $request->file('logo')->store('store-profile', 's3');
            
            // Dapatkan URL publik dari file yang disimpan
            $profile->logo = Storage::disk('s3')->url($logoPath);
        } else {
            $profile->logo = Storage::disk('s3')->url('default-logo.png');
        }

        $profile->save();
        return redirect()->route('store-profile.index')->with('success', 'Profile toko berhasil dibuat.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = StoreProfile::first();

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($profile->logo) {
                $oldLogoPath = parse_url($profile->logo, PHP_URL_PATH);
                $oldLogoPath = ltrim($oldLogoPath, '/');
                Storage::disk('s3')->delete($oldLogoPath);
            }
            
            // Simpan logo baru
            $logoPath = $request->file('logo')->store('store-profile', 's3');
            $profile->logo = Storage::disk('s3')->url($logoPath);
        }

        $profile->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('store-profile.index')->with('success', 'Profile toko berhasil diperbarui.');
    }
}