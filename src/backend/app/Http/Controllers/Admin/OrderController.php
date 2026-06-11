<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Daftar semua pesanan di seluruh platform.
     * Admin hanya bisa monitor — tidak bisa mengubah status pesanan.
     */
    public function index(Request $request)
    {
        $status   = $request->query('status', 'all');
        $search   = $request->query('search');

        $query = Order::with(['user', 'merchant', 'items'])
            ->orderByDesc('ordered_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pickup_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('merchant', fn($m) => $m->where('business_name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $counts = [
            'all'       => Order::count(),
            'pending'   => Order::where('status', Order::STATUS_PENDING)->count(),
            'confirmed' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            'ready'     => Order::where('status', Order::STATUS_READY)->count(),
            'completed' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'rejected'  => Order::where('status', Order::STATUS_REJECTED)->count(),
            'expired'   => Order::where('status', Order::STATUS_EXPIRED)->count(),
        ];

        return view('admin.orders.index', compact('orders', 'status', 'counts', 'search'));
    }

    /**
     * Detail satu pesanan — admin bisa lihat semua info termasuk items dan payments.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'merchant', 'items.listing', 'payments']);

        return view('admin.orders.show', compact('order'));
    }
}