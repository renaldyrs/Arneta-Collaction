<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class StoreProfile extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'logo',
    ];

    public function getLogoUrlAttribute()
    {
        if (empty($this->logo)) {
            return asset('images/default-logo.png');
        }

        // Jika sudah URL lengkap
        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        // Jika path relatif dari storage
        return asset('storage/' . $this->logo);
    }
}
