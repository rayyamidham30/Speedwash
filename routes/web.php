<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\BookingController as CustomerBooking;
use App\Http\Controllers\Customer\ProfileController as CustomerProfile;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BookingController as AdminBooking;
use App\Http\Controllers\Admin\CustomerController as AdminCustomer;
use App\Http\Controllers\Admin\ServicePackageController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

// Home redirect
Route::get('/', fn() => redirect()->route('login'));

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Customer Routes
Route::middleware(['auth'])->prefix('dashboard')->name('customer.')->group(function () {
    Route::get('/', [CustomerDashboard::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('/booking', [CustomerBooking::class, 'index'])->name('bookings.index');
    Route::get('/booking/create', [CustomerBooking::class, 'create'])->name('bookings.create');
    Route::post('/booking', [CustomerBooking::class, 'store'])->name('bookings.store');
    Route::get('/booking/{booking}', [CustomerBooking::class, 'show'])->name('bookings.show');
    Route::post('/booking/{booking}/cancel', [CustomerBooking::class, 'cancel'])->name('bookings.cancel');

    // Queue
    Route::get('/antrian', [CustomerBooking::class, 'queue'])->name('queue');

    // Profile
    Route::get('/profil', [CustomerProfile::class, 'index'])->name('profile');
    Route::put('/profil', [CustomerProfile::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [CustomerProfile::class, 'updatePassword'])->name('profile.password');
    Route::post('/profil/vehicle', [CustomerProfile::class, 'storeVehicle'])->name('vehicles.store');
    Route::delete('/profil/vehicle/{vehicle}', [CustomerProfile::class, 'destroyVehicle'])->name('vehicles.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Bookings management
    Route::get('/booking', [AdminBooking::class, 'index'])->name('bookings.index');
    Route::get('/booking/{booking}', [AdminBooking::class, 'show'])->name('bookings.show');
    Route::post('/booking/{booking}/status', [AdminBooking::class, 'updateStatus'])->name('bookings.status');

    // Queue management
    Route::get('/antrian', [AdminBooking::class, 'queue'])->name('queue');
    Route::post('/antrian/update', [AdminBooking::class, 'updateQueue'])->name('queue.update');

    // Customer management
    Route::get('/pelanggan', [AdminCustomer::class, 'index'])->name('customers.index');
    Route::get('/pelanggan/{user}', [AdminCustomer::class, 'show'])->name('customers.show');
    Route::post('/pelanggan/{user}/toggle', [AdminCustomer::class, 'toggleStatus'])->name('customers.toggle');

    // Service packages
    Route::resource('paket', ServicePackageController::class)->names([
        'index' => 'packages.index',
        'create' => 'packages.create',
        'store' => 'packages.store',
        'edit' => 'packages.edit',
        'update' => 'packages.update',
        'destroy' => 'packages.destroy',
    ]);

    // Reports
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports');
});
