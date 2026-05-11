<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('eTrike')->latest()->paginate(10);
        return view('admin.drivers.index', compact('drivers'));
    }
    
    public function create()
    {
        return view('admin.drivers.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers',
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|unique:drivers',
        ]);
        
        Driver::create($validated);
        
        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver created successfully.');
    }
    
    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }
    
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
            'status' => 'required|in:available,on_trip,offline',
        ]);
        
        $driver->update($validated);
        
        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver updated successfully.');
    }
    
    public function destroy(Driver $driver)
    {
        // Check if driver has any active bookings
        if ($driver->bookings()->whereNotIn('status', ['completed', 'cancelled'])->exists()) {
            return back()->with('error', 'Cannot delete driver with active bookings.');
        }
        
        $driver->delete();
        
        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }
    
    public function toggleStatus(Driver $driver)
    {
        $newStatus = $driver->status === 'available' ? 'offline' : 'available';
        $driver->update(['status' => $newStatus]);
        
        return back()->with('success', "Driver status changed to {$newStatus}.");
    }
}