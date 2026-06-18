<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vehicles = Vehicle::where('user_id', $user->id)->get();
        return view('customer.profile', compact('user', 'vehicles'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'phone' => ['required', 'string', 'min:10', 'max:15'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak benar.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles'],
            'color' => ['nullable', 'string', 'max:30'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:' . date('Y')],
            'type' => ['required', 'in:matic,manual,sport,listrik'],
        ], [
            'brand.required' => 'Merek motor wajib diisi.',
            'model.required' => 'Model motor wajib diisi.',
            'license_plate.required' => 'Plat nomor wajib diisi.',
            'license_plate.unique' => 'Plat nomor sudah terdaftar.',
        ]);

        $validated['user_id'] = Auth::id();

        Vehicle::create($validated);

        return back()->with('success', 'Motor berhasil ditambahkan.');
    }

    public function destroyVehicle(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $vehicle->update(['is_active' => false]);
        return back()->with('success', 'Motor berhasil dihapus.');
    }
}
