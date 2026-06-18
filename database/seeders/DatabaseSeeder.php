<?php

namespace Database\Seeders;

use App\Models\ServicePackage;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\QueueMonitor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin SPEEDWASH',
            'email' => 'admin@speedwash.id',
            'password' => Hash::make('password123'),
            'phone' => '08123456789',
            'address' => 'Jl. Teknologi No. 1, Malang',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Service Packages
        $packages = [
            [
                'name' => 'Cuci Express',
                'code' => 'EXPRESS',
                'description' => 'Cuci cepat 15 menit untuk motor harian Anda',
                'price' => 15000,
                'duration_minutes' => 15,
                'features' => ['Semprot air bertekanan', 'Sabun busa', 'Bilas bersih', 'Lap kering'],
                'color' => '#3B82F6',
                'icon' => 'bi-lightning-charge',
                'sort_order' => 1,
            ],
            [
                'name' => 'Cuci Reguler',
                'code' => 'REGULER',
                'description' => 'Cuci menyeluruh dengan perawatan standar',
                'price' => 25000,
                'duration_minutes' => 30,
                'features' => ['Semprot air bertekanan', 'Sabun premium', 'Sikat detail', 'Bilas bersih', 'Lap microfiber', 'Semir ban'],
                'color' => '#10B981',
                'icon' => 'bi-droplet-fill',
                'sort_order' => 2,
            ],
            [
                'name' => 'Cuci Premium',
                'code' => 'PREMIUM',
                'description' => 'Perawatan lengkap dengan wax dan poles',
                'price' => 45000,
                'duration_minutes' => 45,
                'features' => ['Semprot air bertekanan', 'Sabun premium', 'Sikat detail menyeluruh', 'Bilas bersih', 'Lap microfiber', 'Semir ban', 'Wax body', 'Polish kaca'],
                'color' => '#F59E0B',
                'icon' => 'bi-stars',
                'sort_order' => 3,
            ],
            [
                'name' => 'Cuci + Salon Motor',
                'code' => 'SALON',
                'description' => 'Paket terlengkap termasuk poles dan perawatan mesin',
                'price' => 85000,
                'duration_minutes' => 90,
                'features' => ['Cuci premium lengkap', 'Poles body menyeluruh', 'Coating nano', 'Perawatan rantai', 'Pengecekan oli', 'Pembersih karburator', 'Aroma terapi interior'],
                'color' => '#8B5CF6',
                'icon' => 'bi-gem',
                'sort_order' => 4,
            ],
        ];

        foreach ($packages as $package) {
            ServicePackage::create($package);
        }

        // Create sample customers
        $customers = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '08111111111'],
            ['name' => 'Siti Rahayu', 'email' => 'siti@example.com', 'phone' => '08222222222'],
            ['name' => 'Agus Wijaya', 'email' => 'agus@example.com', 'phone' => '08333333333'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '08444444444'],
        ];

        foreach ($customers as $customerData) {
            $customer = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => Hash::make('password123'),
                'phone' => $customerData['phone'],
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Create vehicles for each customer
            Vehicle::create([
                'user_id' => $customer->id,
                'brand' => 'Honda',
                'model' => 'Beat',
                'license_plate' => 'N ' . rand(1000, 9999) . ' ' . chr(rand(65, 90)) . chr(rand(65, 90)),
                'color' => 'Putih',
                'year' => 2022,
                'type' => 'matic',
            ]);
        }

        // Create today's queue monitor
        QueueMonitor::create([
            'date' => today(),
            'current_serving' => 3,
            'total_queue' => 8,
            'available_slots' => 20,
            'is_open' => true,
            'open_time' => '08:00:00',
            'close_time' => '20:00:00',
        ]);

        // Create sample bookings
        $customerUsers = User::where('role', 'customer')->get();
        $servicePackagesList = ServicePackage::all();
        $statuses = ['completed', 'completed', 'completed', 'in_progress', 'in_queue'];

        foreach ($customerUsers as $index => $cust) {
            $vehicle = $cust->vehicles->first();
            if (!$vehicle) continue;

            $pkg = $servicePackagesList->random();
            $status = $statuses[$index % count($statuses)];
            $scheduledAt = Carbon::now()->subDays(rand(1, 30));

            $booking = Booking::create([
                'booking_code' => 'SW-' . strtoupper(uniqid()),
                'user_id' => $cust->id,
                'vehicle_id' => $vehicle->id,
                'service_package_id' => $pkg->id,
                'scheduled_at' => $scheduledAt,
                'status' => $status,
                'queue_number' => rand(1, 20),
                'total_price' => $pkg->price,
                'payment_status' => $status === 'completed' ? 'paid' : 'unpaid',
                'payment_method' => 'cash',
                'started_at' => in_array($status, ['in_progress', 'completed']) ? $scheduledAt->copy()->addMinutes(5) : null,
                'completed_at' => $status === 'completed' ? $scheduledAt->copy()->addMinutes($pkg->duration_minutes + 5) : null,
                'rating' => $status === 'completed' ? rand(4, 5) : null,
            ]);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('👤 Admin: admin@speedwash.id / password123');
        $this->command->info('👤 Customer: budi@example.com / password123');
    }
}
