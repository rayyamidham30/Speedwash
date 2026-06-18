<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueMonitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'current_serving',
        'total_queue',
        'available_slots',
        'is_open',
        'open_time',
        'close_time',
    ];

    protected $casts = [
        'date' => 'date',
        'is_open' => 'boolean',
    ];

    public static function today(): self
    {
        return self::firstOrCreate(
            ['date' => today()],
            [
                'current_serving' => 0,
                'total_queue' => 0,
                'available_slots' => 20,
                'is_open' => true,
                'open_time' => '08:00:00',
                'close_time' => '20:00:00',
            ]
        );
    }

    public function getRemainingWaitAttribute(): int
    {
        $remaining = $this->total_queue - $this->current_serving;
        return max(0, $remaining);
    }

    public function getEstimatedWaitMinutesAttribute(): int
    {
        $avgServiceTime = 25; // rata-rata 25 menit per motor
        return $this->remaining_wait * $avgServiceTime;
    }
}
