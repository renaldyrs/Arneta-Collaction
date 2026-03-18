<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_name',
        'user_id',
        'customer_id',
        'payment_method_id',
        'discount_id',
        'total_amount',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function details()
    {
        return $this->hasMany(PendingTransactionDetail::class);
    }
}
