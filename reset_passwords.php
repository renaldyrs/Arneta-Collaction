<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$users = User::all();
foreach ($users as $user) {
    $user->password = Hash::make('password123');
    $user->save();
    echo "Password direset: " . $user->email . "\n";
}
echo "Selesai. Password semua user menjadi: password123\n";
