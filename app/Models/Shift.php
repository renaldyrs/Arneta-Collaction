<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_number',
        'user_id',
        'opening_cash',
        'closing_cash',
        'expected_cash',
        'cash_difference',
        'total_transactions',
        'total_revenue',
        'opening_notes',
        'closing_notes',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opening_cash' => 'decimal:2',
        'closing_cash' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'cash_difference' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Generate nomor shift
    public static function generateShiftNumber()
    {
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'SHIFT-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Cek apakah ada shift aktif untuk user
    public static function getActiveShift($userId = null)
    {
        $query = self::where('status', 'open');
        if ($userId) {
            $query->where('user_id', $userId);
        }
        return $query->latest()->first();
    }

    // Durasi shift
    public function getDurationAttribute()
    {
        $start = $this->opened_at ?? $this->created_at;
        $end = $this->closed_at ?? now();
        return $start->diffForHumans($end, true);
    }
}
