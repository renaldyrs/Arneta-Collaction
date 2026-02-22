<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'price',
        'stock',
        'low_stock_threshold',
        'description',
        'image',
        'category_id',
        'supplier_id',
        'barcode',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke sizes
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')
            ->withPivot('stock')
            ->withTimestamps();
    }

    // Relasi ke transaction details
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Cek apakah stok rendah
    public function getIsLowStockAttribute()
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    // Cek apakah habis
    public function getIsOutOfStockAttribute()
    {
        return $this->stock <= 0;
    }

    // Status stok label
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0)
            return 'Habis';
        if ($this->stock <= $this->low_stock_threshold)
            return 'Menipis';
        return 'Tersedia';
    }

    // Status stok color
    public function getStockColorAttribute()
    {
        if ($this->stock <= 0)
            return 'red';
        if ($this->stock <= $this->low_stock_threshold)
            return 'yellow';
        return 'green';
    }

    // Method untuk generate kode produk
    public static function generateProductCode($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $maxCode = Product::where('category_id', $categoryId)
            ->where('code', 'like', $category->code . '%')
            ->max('code');
        $lastNumber = $maxCode ? intval(substr($maxCode, strlen($category->code))) : 0;
        return $category->code . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    // Method untuk generate barcode
    public function generateBarcode()
    {
        $this->barcode = $this->code;
        $this->save();
    }
}