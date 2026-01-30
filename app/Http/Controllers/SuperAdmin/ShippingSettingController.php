<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;

class ShippingSettingController extends Controller
{
    /**
     * Display a listing of shipping settings.
     */
    public function index()
    {
        $shippingSettings = ShippingSetting::all();
        
        return view('superadmin.shipping-settings.index', compact('shippingSettings'));
    }

    /**
     * Show the form for editing the specified shipping setting.
     */
    public function edit(ShippingSetting $shippingSetting)
    {
        return view('superadmin.shipping-settings.edit', compact('shippingSetting'));
    }

    /**
     * Update the specified shipping setting in storage.
     */
    public function update(Request $request, ShippingSetting $shippingSetting)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_km' => 'required|numeric|min:0',
            'min_charge' => 'required|numeric|min:0',
            'max_distance' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox is_active (jika tidak dicentang, set false)
        $validated['is_active'] = $request->has('is_active');

        $shippingSetting->update($validated);

        return redirect()
            ->route('superadmin.shipping-settings.index')
            ->with('success', 'Setting ongkir berhasil diupdate!');
    }

    /**
     * Toggle status aktif/nonaktif (AJAX)
     */
    public function toggleStatus(ShippingSetting $shippingSetting)
    {
        $shippingSetting->update([
            'is_active' => !$shippingSetting->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $shippingSetting->is_active,
            'message' => 'Status berhasil diubah!'
        ]);
    }
}