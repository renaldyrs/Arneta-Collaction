<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'total_amount',
        'payment_amount',
        'change_amount',
        'discount_amount',
        'payment_method_id',
        'user_id',
        'customer_id',
        'discount_id',
        'shift_id',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    // Relasi ke PaymentMethod
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Relasi ke TransactionDetail
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Discount
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    // Relasi ke Shift
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // Scope filter tanggal
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
    }

    // Generate invoice number
    public static function generateInvoiceNumber()
    {
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'INV-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}