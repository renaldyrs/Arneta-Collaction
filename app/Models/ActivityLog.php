<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Static helper untuk log
    public static function log($action, $description, $model = null, $oldValues = null, $newValues = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // Label aksi
    public function getActionLabelAttribute()
    {
        return match ($this->action) {
            'created' => 'Dibuat',
            'updated' => 'Diubah',
            'deleted' => 'Dihapus',
            'login' => 'Login',
            'logout' => 'Logout',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => ucfirst($this->action),
        };
    }

    // Label warna aksi
    public function getActionColorAttribute()
    {
        return match ($this->action) {
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'login' => 'purple',
            'logout' => 'gray',
            'approved' => 'teal',
            'rejected' => 'orange',
            default => 'gray',
        };
    }

    // Nama model yang simple
    public function getModelNameAttribute()
    {
        if (!$this->model_type)
            return '-';
        $parts = explode('\\', $this->model_type);
        return end($parts);
    }
}
