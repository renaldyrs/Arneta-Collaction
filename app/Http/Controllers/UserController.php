<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index(Request $request)
    {
        $users = User::when($request->search, function($query, $search) {
            return $query->search($search);
        })
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();
        
        return view('users.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,kasir',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Alert::success('Berhasil', 'User berhasil ditambahkan.');
        return redirect()->route('users.index');
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Mengupdate user
    public function update(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,kasir',
        ]);

        // Data yang akan diupdate
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Update password jika diisi
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data user
        $user->update($data);

        Alert::success('Berhasil', 'User berhasil diperbarui.');
        return redirect()->route('users.index');
    }

    // Menghapus user
    public function destroy(User $user)
    {
        $user->delete();
        Alert::success('Berhasil', 'User berhasil dihapus.');
        return redirect()->route('users.index');
    }
}