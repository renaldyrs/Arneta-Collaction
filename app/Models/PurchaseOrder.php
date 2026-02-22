<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'status',
        'total_amount',
        'notes',
        'expected_date',
        'received_date',
    ];

    protected $casts = [
        'expected_date' => 'date',
        'received_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Relasi ke supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke detail
    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    // Generate nomor PO
    public static function generatePoNumber()
    {
        $last = self::latest()->first();
        $number = $last ? intval(substr($last->po_number, -4)) + 1 : 1;
        return 'PO-' . date('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Status label
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'ordered' => 'Dipesan',
            'received' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    // Status color
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'ordered' => 'blue',
            'received' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
