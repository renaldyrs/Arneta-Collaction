<?php
namespace App\Http\Controllers;

use App\Models\StoreProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;

class StoreProfileController extends Controller
{
    public function index()
    {
        $storeProfile = StoreProfile::first();
        if (!$storeProfile) {
            return redirect()->route('store-profile.create')->with('error', 'Profile toko belum ada. Silakan buat profile toko terlebih dahulu.');
        }
        $profile = StoreProfile::first();
        return view('store-profile.index', compact('profile'));
    }

    public function create()
    {
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
            // Upload ke LaravelCloud
            $path = $request->file('logo')->store('store-profile', 'laravelcloud');
            
            // Verifikasi upload
            if (!Storage::disk('laravelcloud')->exists($path)) {
                throw new \Exception("Gagal mengupload gambar ke LaravelCloud");
            }
            
            // Simpan URL lengkap ke database
            $profile->logo = Storage::disk('laravelcloud')->url($path);
        } else {
            $profile->logo = Storage::disk('laravelcloud')->url('default-logo.png');
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
            if ($profile->logo && $this->checkUrl($profile->logo)) {
                $oldPath = parse_url($profile->logo, PHP_URL_PATH);
                $oldPath = ltrim($oldPath, '/');
                Storage::disk('laravelcloud')->delete($oldPath);
            }

            // Simpan logo baru
            $path = $request->file('logo')->store(
                'store-profile',
                'laravelcloud',
                ['visibility' => 'public']
            );
            
            $profile->logo = Storage::disk('laravelcloud')->url($path);
        }

        $profile->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('store-profile.index')->with('success', 'Profile toko berhasil diperbarui.');
    }

    protected function checkUrl($url)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->head($url, ['timeout' => 3]);
            return $response->getStatusCode() == 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}