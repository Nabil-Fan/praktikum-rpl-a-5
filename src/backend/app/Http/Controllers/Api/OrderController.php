<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Models\FoodListing;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * OrderController menangani pembuatan dan penampilan order untuk user mobile.
 * Logika bisnis didelegasikan ke OrderService agar controller tidak menjadi god class.
 */
class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * Buat order baru.
     * POST /api/v1/orders
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $foodListing   = FoodListing::findOrFail($request->food_listing_id);
        $paymentMethod = PaymentMethod::from($request->payment_method);

        try {
            $newOrder = $this->orderService->createOrder(
                buyer: $request->user(),
                foodListing: $foodListing,
                quantity: $request->quantity,
                paymentMethod: $paymentMethod,
            );
        } catch (\Exception $businessRuleException) {
            return response()->json([
                'message' => $businessRuleException->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Pesanan berhasil dibuat.',
            'order'   => $this->formatOrderDetail($newOrder),
        ], 201);
    }

    /**
     * Tampilkan daftar riwayat order milik user yang sedang login.
     * GET /api/v1/orders
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
                       ->with(['orderItems.foodListing', 'merchant'])
                       ->orderByDesc('ordered_at')
                       ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada riwayat pesanan.',
                'orders'  => [],
            ]);
        }

        $formattedOrders = $orders->map(fn (Order $order) => $this->formatOrderSummary($order));

        return response()->json([
            'orders' => $formattedOrders,
        ]);
    }

    /**
     * Tampilkan detail satu order milik user yang sedang login.
     * GET /api/v1/orders/{id}
     */
    public function show(Request $request, int $orderId): JsonResponse
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', $request->user()->id)
                      ->with(['orderItems.foodListing', 'merchant', 'payments'])
                      ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'order' => $this->formatOrderDetail($order),
        ]);
    }

    /**
     * Inisialisasi payment record setelah merchant konfirmasi.
     * Dipanggil oleh frontend saat polling detect status = confirmed,
     * sebelum user diarahkan ke halaman checkout/pembayaran.
     *
     * POST /api/v1/orders/{id}/init-payment
     */
    public function initPayment(Request $request, int $orderId): JsonResponse
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', $request->user()->id)
                      ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        try {
            $this->orderService->initPaymentAfterConfirmation($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Payment siap. Silakan lanjutkan ke pembayaran.',
        ]);
    }

    /**
     * Format ringkasan order untuk tampilan list riwayat pesanan.
     * Hanya field yang dibutuhkan untuk card di halaman Riwayat Pesanan.
     */
    private function formatOrderSummary(Order $order): array
    {
        $firstItem = $order->orderItems->first();

        return [
            'id'             => $order->id,
            'pickup_code'    => $order->pickup_code,
            'status'         => $order->status,
            'total_amount'   => $order->total_amount,
            'payment_method' => $order->payment_method,
            'ordered_at'     => $order->ordered_at,
            'merchant_name'  => $order->merchant->business_name ?? null,
            'item_name'      => $firstItem?->listing_name,
            'item_quantity'  => $firstItem?->quantity,
        ];
    }

    /**
     * Format detail order lengkap untuk tampilan Order Detail dan QR Code page.
     * Termasuk lokasi merchant untuk peta dan pickup_code untuk QR.
     */
    private function formatOrderDetail(Order $order): array
    {
        return [
            'id'             => $order->id,
            'pickup_code'    => $order->pickup_code,
            'status'         => $order->status,
            'total_amount'   => $order->total_amount,
            'payment_method' => $order->payment_method,
            'ordered_at'     => $order->ordered_at,
            'expires_at'     => $order->expires_at,
            'confirmed_at'   => $order->confirmed_at,
            'completed_at'   => $order->completed_at,
            'rejected_at'    => $order->rejected_at,
            'merchant' => [
                'id'               => $order->merchant->id ?? null,
                'business_name'    => $order->merchant->business_name ?? null,
                'business_address' => $order->merchant->business_address ?? null,
                'latitude'         => $order->merchant->latitude ?? null,
                'longitude'        => $order->merchant->longitude ?? null,
            ],
            'items' => $order->orderItems->map(fn ($orderItem) => [
                'id'           => $orderItem->id,
                'listing_name' => $orderItem->listing_name,
                'quantity'     => $orderItem->quantity,
                'unit_price'   => $orderItem->unit_price,
                'subtotal'     => $orderItem->subtotal,
            ]),
            'payment' => $order->payments->last() ? [
                'gateway'           => $order->payments->last()->payment_gateway,
                'status'            => $order->payments->last()->status,
                'payment_proof_url' => $order->payments->last()->payment_proof_url,
            ] : null,
        ];
    }
}
