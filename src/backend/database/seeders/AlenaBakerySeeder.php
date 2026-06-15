<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlenaBakerySeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. USER ──────────────────────────────────────────────────────────
        // Cek dulu supaya tidak duplikat kalau seeder dijalankan ulang
        $existingUser = DB::table('users')->where('email', 'alena.bakery@ecoeats.id')->first();

        if ($existingUser) {
            $this->command->warn('User alena.bakery@ecoeats.id sudah ada, skip insert user.');
            $userId = $existingUser->id;
        } else {
            $userId = DB::table('users')->insertGetId([
                'name'          => 'Alena Bakery',
                'email'         => 'alena.bakery@ecoeats.id',
                // Password: alena1234
                'password_hash' => Hash::make('alena1234'),
                'phone'         => '082134567890',
                'role'          => 'merchant',
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ]);
            $this->command->info("User Alena Bakery dibuat (id: {$userId})");
        }

        // ── 2. MERCHANT PROFILE ──────────────────────────────────────────────
        $existingProfile = DB::table('merchant_profiles')->where('user_id', $userId)->first();

        if ($existingProfile) {
            $this->command->warn('Profil merchant sudah ada, skip insert profil.');
            $merchantId = $existingProfile->id;
        } else {
            $merchantId = DB::table('merchant_profiles')->insertGetId([
                'user_id'             => $userId,
                'business_name'       => 'Alena Bakery',
                'business_address'    => 'Jl. Slamet Riyadi No. 142, Sriwedari, Laweyan, Surakarta, Jawa Tengah 57141',
                // Koordinat: kawasan Jl. Slamet Riyadi Solo (pusat kota)
                'latitude'            => -7.5695420,
                'longitude'           => 110.8129580,
                'business_license_url'=> null,
                'halal_cert_url'      => null,
                'verification_status' => 'approved',
                'verified_at'         => now(),
                'created_at'          => now(),
            ]);
            $this->command->info("Profil merchant Alena Bakery dibuat (id: {$merchantId})");
        }

        // ── 3. KATEGORI ──────────────────────────────────────────────────────
        // insertOrIgnore aman karena ada UNIQUE KEY pada kolom name
        DB::table('categories')->insertOrIgnore([
            ['name' => 'Roti & Kue',    'created_at' => now()],
            ['name' => 'Pastry',        'created_at' => now()],
            ['name' => 'Minuman',       'created_at' => now()],
            ['name' => 'Nasi & Mie',    'created_at' => now()],
            ['name' => 'Lauk & Snack',  'created_at' => now()],
        ]);

        $catRotiKue = DB::table('categories')->where('name', 'Roti & Kue')->value('id');
        $catPastry  = DB::table('categories')->where('name', 'Pastry')->value('id');

        // ── 4. FOOD LISTINGS ─────────────────────────────────────────────────
        $now         = now();
        $pickupStart = now()->setTime(16, 0);   // pickup mulai jam 16.00
        $pickupEnd   = now()->setTime(20, 0);   // pickup tutup jam 20.00

        $listings = [
            [
                'merchant_id'    => $merchantId,
                'category_id'    => $catRotiKue,
                'name'           => 'Sourdough Loaf',
                'description'    => 'Roti sourdough artisan panggang pagi ini, tekstur crispy di luar dan lembut di dalam. Cocok untuk sarapan besok pagi.',
                'original_price' => 55000.00,
                'discount_price' => 30000.00,
                'stock_qty'      => 5,
                'photo_url'      => null,
                'pickup_start'   => $pickupStart,
                'pickup_end'     => $pickupEnd,
                'status'         => 'available',
                'created_at'     => $now,
                'updated_at'     => $now,
                'deleted_at'     => null,
            ],
            [
                'merchant_id'    => $merchantId,
                'category_id'    => $catPastry,
                'name'           => 'Croissant Butter (3 pcs)',
                'description'    => 'Croissant mentega klasik, berlapis-lapis dan renyah. Dipanggang fresh setiap pagi. Sisa stok sore ini.',
                'original_price' => 42000.00,
                'discount_price' => 22000.00,
                'stock_qty'      => 8,
                'photo_url'      => null,
                'pickup_start'   => $pickupStart,
                'pickup_end'     => $pickupEnd,
                'status'         => 'available',
                'created_at'     => $now,
                'updated_at'     => $now,
                'deleted_at'     => null,
            ],
            [
                'merchant_id'    => $merchantId,
                'category_id'    => $catPastry,
                'name'           => 'Box Pastry Mix (5 pcs)',
                'description'    => 'Satu box berisi 5 pastry pilihan: pain au chocolat, almond croissant, danish strawberry, cheese roll, dan cinnamon twist.',
                'original_price' => 85000.00,
                'discount_price' => 45000.00,
                'stock_qty'      => 4,
                'photo_url'      => null,
                'pickup_start'   => $pickupStart,
                'pickup_end'     => $pickupEnd,
                'status'         => 'available',
                'created_at'     => $now,
                'updated_at'     => $now,
                'deleted_at'     => null,
            ],
            [
                'merchant_id'    => $merchantId,
                'category_id'    => $catRotiKue,
                'name'           => 'Cinnamon Roll (2 pcs)',
                'description'    => 'Cinnamon roll lembut dengan cream cheese frosting. Masih fresh, dipanggang siang tadi.',
                'original_price' => 38000.00,
                'discount_price' => 20000.00,
                'stock_qty'      => 6,
                'photo_url'      => null,
                'pickup_start'   => $pickupStart,
                'pickup_end'     => $pickupEnd,
                'status'         => 'available',
                'created_at'     => $now,
                'updated_at'     => $now,
                'deleted_at'     => null,
            ],
            [
                'merchant_id'    => $merchantId,
                'category_id'    => $catRotiKue,
                'name'           => 'Banana Loaf Cake',
                'description'    => 'Banana bread homemade dengan walnut, tekstur moist dan harum. Satu loaf utuh cukup untuk 6–8 potong.',
                'original_price' => 65000.00,
                'discount_price' => 35000.00,
                'stock_qty'      => 3,
                'photo_url'      => null,
                'pickup_start'   => $pickupStart,
                'pickup_end'     => $pickupEnd,
                'status'         => 'available',
                'created_at'     => $now,
                'updated_at'     => $now,
                'deleted_at'     => null,
            ],
        ];

        // Cek apakah listing sudah ada untuk merchant ini supaya tidak duplikat
        $existingListingCount = DB::table('food_listings')
            ->where('merchant_id', $merchantId)
            ->whereNull('deleted_at')
            ->count();

        if ($existingListingCount > 0) {
            $this->command->warn("Merchant sudah punya {$existingListingCount} listing, skip insert food listings.");
        } else {
            DB::table('food_listings')->insert($listings);
            $this->command->info('5 food listings Alena Bakery berhasil dibuat.');
        }

        $this->command->newLine();
        $this->command->info('=== Kredensial Login Alena Bakery ===');
        $this->command->info('URL   : /merchant/login');
        $this->command->info('Email : alena.bakery@ecoeats.id');
        $this->command->info('Pass  : alena1234');
        $this->command->info('Status: approved (langsung bisa akses semua fitur merchant)');
    }
}