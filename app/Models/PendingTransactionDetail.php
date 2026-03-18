<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingTransactionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'pending_transaction_id',
        'product_id',
        'quantity',
        'size',
        'price',
        'subtotal',
    ];

    public function pendingTransaction()
    {
        return $this->belongsTo(PendingTransaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
