<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Driver;
use App\Models\ETrike;
use App\Models\FareRate;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@quickserve.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '09123456789',
            'address' => 'Admin Office, City',
        ]);

        // Create Regular Passenger
        User::create([
            'name' => 'John Passenger',
            'email' => 'passenger@example.com',
            'password' => Hash::make('password'),
            'role' => 'passenger',
            'phone' => '09987654321',
            'address' => '123 Main St, City',
        ]);

        // Create Drivers
        Driver::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@driver.com',
            'phone' => '09111234567',
            'license_number' => 'D1234567890',
            'status' => 'available',
            'rating' => 4.8,
            'total_trips' => 150,
        ]);

        Driver::create([
            'name' => 'Maria Santos',
            'email' => 'maria@driver.com',
            'phone' => '09222345678',
            'license_number' => 'D0987654321',
            'status' => 'available',
            'rating' => 4.9,
            'total_trips' => 245,
        ]);

        // Create E-Trikes
        ETrike::create([
            'plate_number' => 'ETK-1234',
            'model' => 'Bajaj RE',
            'capacity' => 4,
            'status' => 'available',
            'driver_id' => 1,
        ]);

        ETrike::create([
            'plate_number' => 'ETK-5678',
            'model' => 'TVS King',
            'capacity' => 3,
            'status' => 'available',
            'driver_id' => 2,
        ]);

        // Create Fare Rates
        FareRate::create([
            'name' => 'Standard Rate',
            'base_fare' => 40.00,
            'per_km_rate' => 10.00,
            'per_minute_rate' => 2.00,
            'booking_fee' => 0,
            'is_active' => true,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin Email: admin@quickserve.com');
        $this->command->info('Admin Password: password');
        $this->command->info('Passenger Email: passenger@example.com');
        $this->command->info('Passenger Password: password');
    }
}