<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->search, function ($query, $search) {
            return $query->search($search);
        })->orderBy('name')->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,kasir',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'user' => $user], 201);
        }

        Alert::success('Berhasil', 'User berhasil ditambahkan.');
        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        if (request()->wantsJson()) {
            return response()->json($user->only('id', 'name', 'email', 'role'));
        }
        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,kasir',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'user' => $user->fresh()]);
        }

        Alert::success('Berhasil', 'User berhasil diperbarui.');
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['message' => 'Tidak bisa menghapus akun sendiri.'], 403);
            }
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        Alert::success('Berhasil', 'User berhasil dihapus.');
        return redirect()->route('users.index');
    }
}