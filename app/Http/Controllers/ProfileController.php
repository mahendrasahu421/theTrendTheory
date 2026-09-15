<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
            'state' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
        ]);

        $user->update($request->only('name', 'phone', 'city', 'state', 'address', 'pincode'));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully!',
                'user' => $this->checkoutUserPayload($user->fresh()),
            ]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function storeAddress(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'type' => ['nullable', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'pincode' => ['required', 'string', 'max:10'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'location_source' => ['nullable', 'string', 'max:30'],
        ]);

        $user->addresses()->update(['is_default' => false]);

        $address = $user->addresses()->create([
            'type' => $validated['type'] ?? 'Home',
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address_line' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'pincode' => $validated['pincode'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'location_source' => $validated['location_source'] ?? null,
            'is_default' => true,
        ]);

        $userUpdate = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'address' => $validated['address'],
            'pincode' => $validated['pincode'],
        ];

        if (!empty($validated['email'])) {
            $userUpdate['email'] = $validated['email'];
        }

        $user->update($userUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Address saved successfully!',
            'address' => $this->addressPayload($address->fresh()),
            'user' => $this->checkoutUserPayload($user->fresh()),
        ]);
    }

    public function setDefaultAddress(Address $address)
    {
        $user = auth()->user();
        abort_unless((int) $address->user_id === (int) $user->id, 403);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        $user->update([
            'name' => $address->name ?: $user->name,
            'phone' => $address->phone ?: $user->phone,
            'city' => $address->city,
            'state' => $address->state,
            'address' => $address->address_line,
            'pincode' => $address->pincode,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delivery address selected.',
            'address' => $this->addressPayload($address->fresh()),
            'user' => $this->checkoutUserPayload($user->fresh()),
        ]);
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

    private function checkoutUserPayload($user): array
    {
        try {
            $addresses = $user->addresses()
                ->orderByDesc('is_default')
                ->latest()
                ->get();
        } catch (\Throwable $e) {
            $addresses = collect();
        }

        $defaultAddress = $addresses->firstWhere('is_default', true) ?: $addresses->first();

        return [
            'name' => optional($defaultAddress)->name ?: $user->name,
            'email' => $user->email,
            'phone' => optional($defaultAddress)->phone ?: $user->phone,
            'address' => optional($defaultAddress)->address_line ?: $user->address,
            'city' => optional($defaultAddress)->city ?: $user->city,
            'state' => optional($defaultAddress)->state ?: $user->state,
            'pincode' => optional($defaultAddress)->pincode ?: $user->pincode,
            'address_id' => optional($defaultAddress)->id,
            'address_type' => optional($defaultAddress)->type ?: 'Home',
            'addresses' => $addresses->map(fn ($address) => $this->addressPayload($address))->values(),
        ];
    }

    private function addressPayload(Address $address): array
    {
        return [
            'id' => $address->id,
            'type' => $address->type ?: 'Home',
            'name' => $address->name,
            'phone' => $address->phone,
            'address' => $address->address_line,
            'city' => $address->city,
            'state' => $address->state,
            'pincode' => $address->pincode,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
            'location_source' => $address->location_source,
            'is_default' => (bool) $address->is_default,
            'full_address' => $address->full_address,
        ];
    }
}
