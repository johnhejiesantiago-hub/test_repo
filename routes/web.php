<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ETrikeController;
use App\Http\Controllers\Admin\FareRateController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Passenger\BookingController as PassengerBookingController;
use Illuminate\Support\Facades\Route;

// Public home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('passenger.dashboard');
    })->name('dashboard');

    // Passenger Routes
    Route::prefix('passenger')->name('passenger.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [PassengerBookingController::class, 'dashboard'])->name('dashboard');
        Route::get('/bookings/create', [PassengerBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [PassengerBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [PassengerBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/cancel', [PassengerBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::get('/bookings', [PassengerBookingController::class, 'history'])->name('bookings.history');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('drivers', DriverController::class);
        Route::post('/drivers/{driver}/toggle-status', [DriverController::class, 'toggleStatus'])->name('drivers.toggle-status');
        Route::resource('e-trikes', ETrikeController::class);
        Route::post('/e-trikes/{e_trike}/toggle-status', [ETrikeController::class, 'toggleStatus'])->name('e-trikes.toggle-status');
        Route::resource('fare-rates', FareRateController::class);
        Route::post('/fare-rates/{fare_rate}/activate', [FareRateController::class, 'activate'])->name('fare-rates.activate');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::get('/reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
        Route::get('/reports/income', [ReportController::class, 'income'])->name('reports.income');
        Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
        Route::post('/bookings/{booking}/confirm', [BookingManagementController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{booking}/assign-driver', [BookingManagementController::class, 'assignDriver'])->name('bookings.assign-driver');
        Route::post('/bookings/{booking}/complete', [BookingManagementController::class, 'complete'])->name('bookings.complete');
    });
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update'); // Add this line
});



require __DIR__.'/auth.php';