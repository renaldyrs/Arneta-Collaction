<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'min_purchase',
        'max_uses',
        'used_count',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
    ];

    // Relasi ke transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Cek apakah diskon valid
    public function isValid($purchaseAmount = 0)
    {
        if (!$this->is_active)
            return false;
        if ($this->start_date && Carbon::now()->lt($this->start_date))
            return false;
        if ($this->end_date && Carbon::now()->gt($this->end_date->endOfDay()))
            return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses)
            return false;
        if ($purchaseAmount < $this->min_purchase)
            return false;
        return true;
    }

    // Hitung nilai diskon
    public function calculateDiscount($amount)
    {
        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        }
        return min($this->value, $amount); // fixed, tapi tidak boleh melebihi total
    }

    // Scope aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }

    // Label tipe
    public function getTypeLabelAttribute()
    {
        return $this->type === 'percentage' ? 'Persentase (%)' : 'Nominal (Rp)';
    }

    // Format value
    public function getFormattedValueAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }
}
