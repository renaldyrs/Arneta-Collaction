<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswords extends Command
{
    protected $signature = 'users:reset-passwords {password=password123}';
    protected $description = 'Reset semua password user ke bcrypt yang valid';

    public function handle()
    {
        $newPassword = $this->argument('password');
        $users = User::all();

        foreach ($users as $user) {
            $user->password = Hash::make($newPassword);
            $user->save();
            $this->info("✓ Reset: {$user->email} (role: {$user->role})");
        }

        $this->newLine();
        $this->info("Selesai! {$users->count()} user berhasil direset.");
        $this->line("Password baru: <fg=yellow>{$newPassword}</>");
    }
}
