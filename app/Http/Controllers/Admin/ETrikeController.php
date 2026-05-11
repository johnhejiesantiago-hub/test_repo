<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\ETrike;
use Illuminate\Http\Request;

class ETrikeController extends Controller
{
    public function index()
    {
        $eTrikes = ETrike::with('driver')->latest()->paginate(10);
        return view('admin.e-trikes.index', compact('eTrikes'));
    }
    
    public function create()
    {
        $drivers = Driver::whereDoesntHave('eTrike')->get();
        return view('admin.e-trikes.create', compact('drivers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:e_trikes',
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:6',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);
        
        ETrike::create($validated);
        
        return redirect()->route('admin.e-trikes.index')
            ->with('success', 'E-Trike added successfully.');
    }
    
    public function edit(ETrike $eTrike)
    {
        $drivers = Driver::whereDoesntHave('eTrike')
            ->orWhere('id', $eTrike->driver_id)
            ->get();
        
        return view('admin.e-trikes.edit', compact('eTrike', 'drivers'));
    }
    
    public function update(Request $request, ETrike $eTrike)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:e_trikes,plate_number,' . $eTrike->id,
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:6',
            'driver_id' => 'nullable|exists:drivers,id',
            'status' => 'required|in:available,booked,maintenance',
        ]);
        
        $eTrike->update($validated);
        
        return redirect()->route('admin.e-trikes.index')
            ->with('success', 'E-Trike updated successfully.');
    }
    
    public function destroy(ETrike $eTrike)
    {
        if ($eTrike->bookings()->whereNotIn('status', ['completed', 'cancelled'])->exists()) {
            return back()->with('error', 'Cannot delete e-trike with active bookings.');
        }
        
        $eTrike->delete();
        
        return redirect()->route('admin.e-trikes.index')
            ->with('success', 'E-Trike deleted successfully.');
    }
    
    public function toggleStatus(ETrike $eTrike)
    {
        $newStatus = $eTrike->status === 'available' ? 'maintenance' : 'available';
        $eTrike->update(['status' => $newStatus]);
        
        return back()->with('success', "E-Trike status changed to {$newStatus}.");
    }
}