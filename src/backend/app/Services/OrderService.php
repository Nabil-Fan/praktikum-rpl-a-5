<?php

namespace App\Services;

use App\Enums\FoodListingStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\FoodListing;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * OrderService menangani seluruh logika bisnis pembuatan dan pengelolaan order.
 *
 * Dipisah dari OrderController agar controller tidak menjadi god class.
 * Controller hanya bertanggung jawab menerima request dan mengembalikan response.
 * Logika ada di sini.
 */
class OrderService
{
    private const ADMIN_FEE_AMOUNT     = 2000;
    private const ORDER_EXPIRY_MINUTES = 30;

    /**
     * Buat order baru untuk user.
     *
     * @throws \Exception jika stok habis atau listing tidak tersedia
     */
    public function createOrder(User $buyer, FoodListing $foodListing, int $quantity, PaymentMethod $paymentMethod): Order
    {
        $this->validateFoodListingAvailability($foodListing, $quantity);

        return DB::transaction(function () use ($buyer, $foodListing, $quantity, $paymentMethod) {
            $this->decrementFoodListingStock($foodListing, $quantity);

            $order = $this->insertOrderRecord($buyer, $foodListing, $quantity, $paymentMethod);

            $this->insertOrderItemRecord($order, $foodListing, $quantity);

            $this->insertPaymentRecord($order, $paymentMethod);

            return $order->load(['orderItems.foodListing', 'merchant', 'payments']);
        });
    }

    /**
     * Pastikan listing tersedia dan stok mencukupi sebelum transaksi dimulai.
     *
     * @throws \Exception
     */
    private function validateFoodListingAvailability(FoodListing $foodListing, int $quantity): void
    {
        if ($foodListing->status !== FoodListingStatus::AVAILABLE) {
            throw new \Exception('Menu ini sudah tidak tersedia.');
        }

        if ($foodListing->stock_qty < $quantity) {
            throw new \Exception("Stok tidak mencukupi. Tersisa {$foodListing->stock_qty} porsi.");
        }
    }

    /**
     * Kurangi stok listing. Jika stok habis, ubah status ke 'sold_out'.
     */
    private function decrementFoodListingStock(FoodListing $foodListing, int $quantity): void
    {
        $remainingStock = $foodListing->stock_qty - $quantity;

        $foodListing->update([
            'stock_qty' => $remainingStock,
            'status'    => $remainingStock === 0
                ? FoodListingStatus::SOLD_OUT->value
                : FoodListingStatus::AVAILABLE->value,
        ]);
    }

    /**
     * Simpan record order ke database.
     */
    private function insertOrderRecord(User $buyer, FoodListing $foodListing, int $quantity, PaymentMethod $paymentMethod): Order
    {
        $itemSubtotal = $foodListing->discount_price * $quantity;
        $totalAmount  = $itemSubtotal + self::ADMIN_FEE_AMOUNT;

        return Order::create([
            'user_id'        => $buyer->id,
            'merchant_id'    => $foodListing->merchant_id,
            'status'         => OrderStatus::PENDING->value,
            'total_amount'   => $totalAmount,
            'payment_method' => $paymentMethod->value,
            'expires_at'     => now()->addMinutes(self::ORDER_EXPIRY_MINUTES),
        ]);
    }

    /**
     * Simpan snapshot item ke order_items.
     * Snapshot (listing_name, unit_price) penting agar riwayat order tidak rusak
     * jika listing diubah atau dihapus merchant di kemudian hari.
     */
    private function insertOrderItemRecord(Order $order, FoodListing $foodListing, int $quantity): void
    {
        $unitPrice = $foodListing->discount_price;

        OrderItem::create([
            'order_id'       => $order->id,
            'food_listing_id'=> $foodListing->id,
            'listing_name'   => $foodListing->name,     // snapshot nama
            'quantity'       => $quantity,
            'unit_price'     => $unitPrice,              // snapshot harga
            'subtotal'       => $unitPrice * $quantity,
        ]);
    }

    /**
     * Simpan record payment awal dengan status 'pending'.
     */
    private function insertPaymentRecord(Order $order, PaymentMethod $paymentMethod): void
    {
        $gatewayName = $this->resolvePaymentGatewayName($paymentMethod);

        Payment::create([
            'order_id'         => $order->id,
            'payment_gateway'  => $gatewayName,
            'amount'           => $order->total_amount,
            'status'           => PaymentStatus::PENDING->value,
            'expired_at'       => now()->addMinutes(self::ORDER_EXPIRY_MINUTES),
        ]);
    }

    /**
     * Tentukan nama gateway berdasarkan metode pembayaran yang dipilih user.
     */
    private function resolvePaymentGatewayName(PaymentMethod $paymentMethod): string
    {
        return match ($paymentMethod) {
            PaymentMethod::QRIS     => 'qris_manual',
            PaymentMethod::TRANSFER => 'transfer_manual',
            PaymentMethod::EWALLET  => 'ewallet_manual',
            PaymentMethod::CASH     => 'cash',
        };
    }
}