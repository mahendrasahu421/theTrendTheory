<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Orders safely load karo — table exist kare tab hi
        try {
            $orders = $user->orders()->latest()->limit(5)->get();
        } catch (\Exception $e) {
            $orders = collect();
        }

        return view('froentend.profile.index', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:150',
            'phone' => 'nullable|string|max:20',
            'city'  => 'nullable|string|max:100',
        ]);

        $user->update($request->only('name', 'phone', 'city'));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully!');
    }
}