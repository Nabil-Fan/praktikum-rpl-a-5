<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ── Helper ────────────────────────────────────────────────────────────

    private function getProfile(): MerchantProfile
    {
        $profile = MerchantProfile::where('user_id', Auth::id())->first();

        if (!$profile) {
            abort(403, 'Profil merchant tidak ditemukan.');
        }

        return $profile;
    }

    // ── Index ─────────────────────────────────────────────────────────────

    /**
     * Daftar semua pesanan milik merchant ini.
     * Filter: status (all / pending / confirmed / ready / completed / rejected / expired)
     */
    public function index(Request $request)
    {
        $profile = $this->getProfile();
        $status  = $request->query('status', 'all');

        $query = Order::forMerchant($profile->id)
            ->with(['items', 'user'])
            ->orderByDesc('ordered_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Hitung badge angka untuk tiap tab
        $counts = [
            'all'       => Order::forMerchant($profile->id)->count(),
            'pending'   => Order::forMerchant($profile->id)->where('status', Order::STATUS_PENDING)->count(),
            'confirmed' => Order::forMerchant($profile->id)->where('status', Order::STATUS_CONFIRMED)->count(),
            'ready'     => Order::forMerchant($profile->id)->where('status', Order::STATUS_READY)->count(),
            'completed' => Order::forMerchant($profile->id)->where('status', Order::STATUS_COMPLETED)->count(),
            'rejected'  => Order::forMerchant($profile->id)->where('status', Order::STATUS_REJECTED)->count(),
            'expired'   => Order::forMerchant($profile->id)->where('status', Order::STATUS_EXPIRED)->count(),
        ];

        return view('merchant.orders.index', compact('orders', 'status', 'counts', 'profile'));
    }

    // ── Show ──────────────────────────────────────────────────────────────

    /**
     * Detail satu pesanan beserta item-itemnya.
     */
    public function show(Order $order)
    {
        $profile = $this->getProfile();

        // Pastikan pesanan ini milik merchant yang sedang login
        if ($order->merchant_id !== $profile->id) {
            abort(403);
        }

        $order->load(['items.listing', 'user', 'payments']);

        return view('merchant.orders.show', compact('order', 'profile'));
    }

    // ── Confirm ───────────────────────────────────────────────────────────

    /**
     * Merchant konfirmasi pesanan (pending → confirmed).
     */
    public function confirm(Order $order)
    {
        $profile = $this->getProfile();

        if ($order->merchant_id !== $profile->id) {
            abort(403);
        }

        if (!$order->isActionable()) {
            return back()->with('error', 'Pesanan ini tidak bisa dikonfirmasi — status saat ini: ' . $order->statusLabel());
        }

        $order->update([
            'status'       => Order::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);

        return back()->with('success', "Pesanan #{$order->pickup_code} berhasil dikonfirmasi.");
    }

    // ── Reject ────────────────────────────────────────────────────────────

    /**
     * Merchant tolak pesanan (pending → rejected).
     * Alasan penolakan wajib diisi.
     */
    public function reject(Request $request, Order $order)
    {
        $profile = $this->getProfile();

        if ($order->merchant_id !== $profile->id) {
            abort(403);
        }

        if (!$order->isActionable()) {
            return back()->with('error', 'Pesanan ini tidak bisa ditolak — status saat ini: ' . $order->statusLabel());
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $order) {
            $order->update([
                'status'      => Order::STATUS_REJECTED,
                'rejected_at' => now(),
            ]);

            // Kembalikan stok listing yang dipesan
            foreach ($order->items as $item) {
                if ($item->listing) {
                    $item->listing->increment('stock_qty', $item->quantity);
                }
            }
        });

        return back()->with('success', "Pesanan #{$order->pickup_code} ditolak.");
    }

    // ── Mark Ready ────────────────────────────────────────────────────────

    /**
     * Merchant tandai pesanan siap diambil (confirmed → ready).
     */
    public function markReady(Order $order)
    {
        $profile = $this->getProfile();

        if ($order->merchant_id !== $profile->id) {
            abort(403);
        }

        if (!$order->canMarkReady()) {
            return back()->with('error', 'Pesanan belum dikonfirmasi atau sudah dalam status lain.');
        }

        $order->update(['status' => Order::STATUS_READY]);

        return back()->with('success', "Pesanan #{$order->pickup_code} ditandai siap diambil.");
    }

    // ── Complete ──────────────────────────────────────────────────────────

    /**
     * Merchant tandai pesanan selesai setelah user pickup (ready → completed).
     * Merchant memverifikasi kode pickup yang ditunjukkan user.
     */
    public function complete(Request $request, Order $order)
    {
        $profile = $this->getProfile();

        if ($order->merchant_id !== $profile->id) {
            abort(403);
        }

        if (!$order->canComplete()) {
            return back()->with('error', 'Pesanan belum dalam status Siap Diambil.');
        }

        $request->validate([
            'pickup_code' => ['required', 'string'],
        ]);

        // Verifikasi kode pickup yang diinput merchant cocok dengan kode pesanan
        if (strtoupper(trim($request->pickup_code)) !== strtoupper($order->pickup_code)) {
            return back()->with('error', 'Kode pickup tidak cocok. Periksa kembali kode dari user.');
        }

        $order->update([
            'status'       => Order::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return back()->with('success', "Pesanan #{$order->pickup_code} selesai. Terima kasih!");
    }
}