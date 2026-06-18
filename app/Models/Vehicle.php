<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'license_plate',
        'color',
        'year',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->brand} {$this->model} - {$this->license_plate}";
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'matic' => 'bi-speedometer2',
            'manual' => 'bi-gear-fill',
            'sport' => 'bi-lightning-charge-fill',
            'listrik' => 'bi-ev-front',
            default => 'bi-bicycle',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'matic' => 'Matic',
            'manual' => 'Manual',
            'sport' => 'Sport',
            'listrik' => 'Listrik',
            default => 'Lainnya',
        };
    }
}
