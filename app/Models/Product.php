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
        'description',
        'image',
        'category_id',
        'barcode', // Tambahkan barcode ke fillable
    ];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
{
    return $this->belongsToMany(Size::class, 'product_sizes')
        ->withPivot('stock')
        ->withTimestamps();
}

    // Method untuk generate kode produk
    public static function generateProductCode($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        

        // Hitung jumlah produk dalam kategori ini
        $maxCode = Product::where('category_id', $categoryId)
        ->where('code', 'like', $category->code.'%')
        ->max('code');

        // Format kode produk: KODEKATEGORI-NOMORURUT
        $lastNumber = $maxCode ? intval(substr($maxCode, strlen($category->code))) : 0;
    return $category->code . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }










    // Method untuk generate barcode
    public function generateBarcode()
    {
        $this->barcode = $this->code; // Gunakan kode produk sebagai barcode
        $this->save();
    }

    
}