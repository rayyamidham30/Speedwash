<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicePackage;
use Illuminate\Http\Request;

class ServicePackageController extends Controller
{
    public function index()
    {
        $packages = ServicePackage::withCount('bookings')->orderBy('sort_order')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:service_packages', 'alpha_dash'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:5'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:100'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        ServicePackage::create($validated);
        return redirect()->route('admin.packages.index')->with('success', 'Paket layanan berhasil ditambahkan.');
    }

    public function edit(ServicePackage $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, ServicePackage $package)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:5'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:100'],
            'color' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $package->update($validated);
        return redirect()->route('admin.packages.index')->with('success', 'Paket layanan berhasil diperbarui.');
    }

    public function destroy(ServicePackage $package)
    {
        if ($package->bookings()->exists()) {
            return back()->withErrors(['delete' => 'Paket tidak bisa dihapus karena sudah memiliki booking.']);
        }
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Paket layanan berhasil dihapus.');
    }
}
