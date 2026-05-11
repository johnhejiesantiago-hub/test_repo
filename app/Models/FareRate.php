<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FareRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_fare',
        'per_km_rate',
        'per_minute_rate',
        'booking_fee',
        'is_active',
    ];

    public function calculateFare($distanceKm, $estimatedMinutes = 0)
    {
        $fare = $this->base_fare;
        $fare += $distanceKm * $this->per_km_rate;
        $fare += $estimatedMinutes * $this->per_minute_rate;
        $fare += $this->booking_fee;
        
        return round($fare, 2);
    }

    public static function getActiveRate()
    {
        return self::where('is_active', true)->first();
    }
}
