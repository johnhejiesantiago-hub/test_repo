<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FareRate;
use Illuminate\Http\Request;

class FareRateController extends Controller
{
    public function index()
    {
        $fareRates = FareRate::latest()->paginate(10);
        return view('admin.fare-rates.index', compact('fareRates'));
    }
    
    public function create()
    {
        return view('admin.fare-rates.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_fare' => 'required|numeric|min:0',
            'per_km_rate' => 'required|numeric|min:0',
            'per_minute_rate' => 'required|numeric|min:0',
            'booking_fee' => 'nullable|numeric|min:0',
        ]);
        
        // Set current active rate to inactive
        FareRate::where('is_active', true)->update(['is_active' => false]);
        
        $validated['is_active'] = true;
        FareRate::create($validated);
        
        return redirect()->route('admin.fare-rates.index')
            ->with('success', 'Fare rate created and activated successfully.');
    }
    
    public function edit(FareRate $fareRate)
    {
        return view('admin.fare-rates.edit', compact('fareRate'));
    }
    
    public function update(Request $request, FareRate $fareRate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_fare' => 'required|numeric|min:0',
            'per_km_rate' => 'required|numeric|min:0',
            'per_minute_rate' => 'required|numeric|min:0',
            'booking_fee' => 'nullable|numeric|min:0',
        ]);
        
        $fareRate->update($validated);
        
        return redirect()->route('admin.fare-rates.index')
            ->with('success', 'Fare rate updated successfully.');
    }
    
    public function destroy(FareRate $fareRate)
    {
        if ($fareRate->is_active) {
            return back()->with('error', 'Cannot delete the active fare rate.');
        }
        
        $fareRate->delete();
        
        return redirect()->route('admin.fare-rates.index')
            ->with('success', 'Fare rate deleted successfully.');
    }
    
    public function activate(FareRate $fareRate)
    {
        // Deactivate all other rates
        FareRate::where('is_active', true)->update(['is_active' => false]);
        
        // Activate selected rate
        $fareRate->update(['is_active' => true]);
        
        return back()->with('success', 'Fare rate activated successfully.');
    }
}