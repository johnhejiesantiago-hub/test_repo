<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'license_number',
        'status',
        'rating',
        'total_trips',
    ];

    // Relationships
    public function eTrike()
    {
        return $this->hasOne(ETrike::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }
}
