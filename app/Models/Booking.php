<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'vehicle_id',
        'service_package_id',
        'scheduled_at',
        'status',
        'queue_number',
        'notes',
        'total_price',
        'payment_status',
        'payment_method',
        'started_at',
        'completed_at',
        'rating',
        'review',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'in_queue' => 'Dalam Antrian',
            'in_progress' => 'Sedang Dicuci',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'in_queue' => 'primary',
            'in_progress' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'light',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bi-clock',
            'confirmed' => 'bi-check-circle',
            'in_queue' => 'bi-list-ol',
            'in_progress' => 'bi-droplet-fill',
            'completed' => 'bi-check2-circle',
            'cancelled' => 'bi-x-circle',
            default => 'bi-question-circle',
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'unpaid' => 'Belum Bayar',
            'paid' => 'Sudah Bayar',
            'refunded' => 'Dikembalikan',
            default => 'Unknown',
        };
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'in_queue', 'in_progress']);
    }

    public static function generateCode(): string
    {
        do {
            $code = 'SW-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }
}
