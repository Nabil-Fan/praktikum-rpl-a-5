<?php
// database/seeders/TestOrderSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;

class TestOrderSeeder extends Seeder
{
    public function run(): void
    {
        // ── ID sudah pasti dari DB ─────────────────────────────────────
        $userId         = 1;  // user@test.com
        $userId2        = 7;  // funix@test.com
        $merchantAlena  = 1;  // Alena Bakery (approved)
        $merchantSPPG   = 2;  // Satgas SPPG (approved)

        // Food listing IDs yang sudah ada
        $sourdough   = 1;  // Sourdough Loaf          — merchant 1
        $arsenalCake = 2;  // Arsenal Cake (3 pcs)    — merchant 1
        $boxPastry   = 3;  // Box Pastry Mix (5 pcs)  — merchant 1
        $cinnamonRoll= 4;  // Cinnamon Roll (2 pcs)   — merchant 1
        $sisaMBG     = 7;  // sisa MBG                — merchant 2

        // ── Order 1: PENDING (baru masuk, belum dikonfirmasi) ──────────
        $o1 = Order::create([
            'user_id'        => $userId,
            'merchant_id'    => $merchantAlena,
            'status'         => Order::STATUS_PENDING,
            'total_amount'   => 22000.00,
            'payment_method' => 'qris',
            'ordered_at'     => now()->subMinutes(8),
            'expires_at'     => now()->addMinutes(52),
        ]);
        DB::table('order_items')->insert([
            'order_id'        => $o1->id,
            'food_listing_id' => $arsenalCake,
            'listing_name'    => 'Arsenal Cake (3 pcs)',
            'quantity'        => 1,
            'unit_price'      => 22000.00,
            'subtotal'        => 22000.00,
        ]);

        // ── Order 2: PENDING (multi-item) ─────────────────────────────
        $o2 = Order::create([
            'user_id'        => $userId2,
            'merchant_id'    => $merchantAlena,
            'status'         => Order::STATUS_PENDING,
            'total_amount'   => 65000.00,
            'payment_method' => 'transfer',
            'ordered_at'     => now()->subMinutes(3),
            'expires_at'     => now()->addMinutes(57),
        ]);
        DB::table('order_items')->insert([
            [
                'order_id'        => $o2->id,
                'food_listing_id' => $boxPastry,
                'listing_name'    => 'Box Pastry Mix (5 pcs)',
                'quantity'        => 1,
                'unit_price'      => 45000.00,
                'subtotal'        => 45000.00,
            ],
            [
                'order_id'        => $o2->id,
                'food_listing_id' => $cinnamonRoll,
                'listing_name'    => 'Cinnamon Roll (2 pcs)',
                'quantity'        => 1,
                'unit_price'      => 20000.00,
                'subtotal'        => 20000.00,
            ],
        ]);

        // ── Order 3: CONFIRMED ─────────────────────────────────────────
        $o3 = Order::create([
            'user_id'        => $userId,
            'merchant_id'    => $merchantAlena,
            'status'         => Order::STATUS_CONFIRMED,
            'total_amount'   => 45000.00,
            'payment_method' => 'ewallet',
            'ordered_at'     => now()->subMinutes(90),
            'expires_at'     => now()->addMinutes(30),
            'confirmed_at'   => now()->subMinutes(75),
        ]);
        DB::table('order_items')->insert([
            'order_id'        => $o3->id,
            'food_listing_id' => $boxPastry,
            'listing_name'    => 'Box Pastry Mix (5 pcs)',
            'quantity'        => 1,
            'unit_price'      => 45000.00,
            'subtotal'        => 45000.00,
        ]);

        // ── Order 4: READY (tinggal tunggu user pickup) ────────────────
        $o4 = Order::create([
            'user_id'        => $userId2,
            'merchant_id'    => $merchantAlena,
            'status'         => Order::STATUS_READY,
            'total_amount'   => 30000.00,
            'payment_method' => 'cash',
            'ordered_at'     => now()->subHours(3),
            'expires_at'     => now()->addHours(1),
            'confirmed_at'   => now()->subHours(2),
        ]);
        DB::table('order_items')->insert([
            'order_id'        => $o4->id,
            'food_listing_id' => $sourdough,
            'listing_name'    => 'Sourdough Loaf',
            'quantity'        => 1,
            'unit_price'      => 30000.00,
            'subtotal'        => 30000.00,
        ]);

        // ── Order 5: COMPLETED (selesai kemarin) ───────────────────────
        $o5 = Order::create([
            'user_id'        => $userId,
            'merchant_id'    => $merchantAlena,
            'status'         => Order::STATUS_COMPLETED,
            'total_amount'   => 42000.00,
            'payment_method' => 'qris',
            'ordered_at'     => now()->subDay()->subHours(2),
            'expires_at'     => now()->subDay()->addHours(2),
            'confirmed_at'   => now()->subDay()->subHours(1),
            'completed_at'   => now()->subDay(),
        ]);
        DB::table('order_items')->insert([
            'order_id'        => $o5->id,
            'food_listing_id' => $arsenalCake,
            'listing_name'    => 'Arsenal Cake (3 pcs)',
            'quantity'        => 1,
            'unit_price'      => 22000.00,
            'subtotal'        => 22000.00,
        ]);

        // ── Order 6: COMPLETED (merchant SPPG) ────────────────────────
        $o6 = Order::create([
            'user_id'        => $userId,
            'merchant_id'    => $merchantSPPG,
            'status'         => Order::STATUS_COMPLETED,
            'total_amount'   => 14000.00,
            'payment_method' => 'cash',
            'ordered_at'     => now()->subDays(2),
            'expires_at'     => now()->subDays(2)->addHours(2),
            'confirmed_at'   => now()->subDays(2)->addMinutes(20),
            'completed_at'   => now()->subDays(2)->addHours(1),
        ]);
        DB::table('order_items')->insert([
            [
                'order_id'        => $o6->id,
                'food_listing_id' => $sisaMBG,
                'listing_name'    => 'sisa MBG',
                'quantity'        => 2,
                'unit_price'      => 7000.00,
                'subtotal'        => 14000.00,
            ],
        ]);

        // ── Order 7: REJECTED (dengan alasan) ─────────────────────────
        $o7 = Order::create([
            'user_id'             => $userId2,
            'merchant_id'         => $merchantAlena,
            'status'              => Order::STATUS_REJECTED,
            'total_amount'        => 20000.00,
            'payment_method'      => 'transfer',
            'ordered_at'          => now()->subDays(3),
            'expires_at'          => now()->subDays(3)->addHours(2),
            'rejected_at'         => now()->subDays(3)->addMinutes(25),
            'cancelled_at'        => now()->subDays(3)->addMinutes(25),
            'cancelled_by'        => 'merchant',
            'cancellation_reason' => 'Stok habis sebelum sempat update di aplikasi.',
        ]);
        DB::table('order_items')->insert([
            'order_id'        => $o7->id,
            'food_listing_id' => $cinnamonRoll,
            'listing_name'    => 'Cinnamon Roll (2 pcs)',
            'quantity'        => 1,
            'unit_price'      => 20000.00,
            'subtotal'        => 20000.00,
        ]);

        // ── Order 8: EXPIRED ──────────────────────────────────────────
        $o8 = Order::create([
            'user_id'        => $userId,
            'merchant_id'    => $merchantSPPG,
            'status'         => Order::STATUS_EXPIRED,
            'total_amount'   => 7000.00,
            'payment_method' => 'qris',
            'ordered_at'     => now()->subDays(4),
            'expires_at'     => now()->subDays(4)->addHour(),
            'expired_at'     => now()->subDays(4)->addHour(),
        ]);
        DB::table('order_items')->insert([
            'order_id'        => $o8->id,
            'food_listing_id' => $sisaMBG,
            'listing_name'    => 'sisa MBG',
            'quantity'        => 1,
            'unit_price'      => 7000.00,
            'subtotal'        => 7000.00,
        ]);

        $this->command->info('✅ TestOrderSeeder selesai — 8 orders dibuat:');
        $this->command->info('   pending(2) confirmed(1) ready(1) completed(2) rejected(1) expired(1)');
        $this->command->info('   Alena Bakery: 6 orders | Satgas SPPG: 2 orders');
    }
}