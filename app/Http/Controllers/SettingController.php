<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Show the settings edit form
    public function index()
    {
        $setting = Setting::first(); // Retrieve the first settings record (assuming only one record exists)
        return view('settings', compact('setting'));
    }

    // Update the settings
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'copyright' => 'nullable|string|max:255',
            'other_info' => 'nullable|string',
            'gst' => 'nullable|string',
        ]);

        // Retrieve the first settings record
        $setting = Setting::first();

        // Handle logo file upload if present
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path; // Store the file path
        }

        // Update the settings
        $setting->update($validated);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}

