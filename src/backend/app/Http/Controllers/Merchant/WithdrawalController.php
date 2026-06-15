<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    // ── Helper ────────────────────────────────────────────────────────

    private function getProfile(): MerchantProfile
    {
        $profile = MerchantProfile::where('user_id', Auth::id())->first();
        abort_if(!$profile, 403, 'Profil merchant tidak ditemukan.');
        return $profile;
    }

    /**
     * Hitung saldo yang bisa ditarik.
     * Saldo = total payments paid untuk order completed merchant ini
     *         dikurangi total withdrawals completed/processing merchant ini.
     *
     * Catatan: payments masih kosong di fase ini karena integrasi payment
     * gateway belum ada. Sementara saldo dihitung dari total_amount order
     * completed sebagai pendekatan sederhana.
     */
    private function calculateBalance(MerchantProfile $profile): array
    {
        // Total pendapatan dari order completed
        $totalRevenue = Order::forMerchant($profile->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total_amount');

        // Total withdrawal yang sudah selesai atau sedang diproses
        $totalWithdrawn = Withdrawal::forMerchant($profile->id)
            ->whereIn('status', [
                Withdrawal::STATUS_COMPLETED,
                Withdrawal::STATUS_PROCESSING,
            ])
            ->sum('amount');

        $balance = max(0, $totalRevenue - $totalWithdrawn);

        return [
            'total_revenue'   => (float) $totalRevenue,
            'total_withdrawn' => (float) $totalWithdrawn,
            'balance'         => (float) $balance,
        ];
    }

    // ── Index ─────────────────────────────────────────────────────────

    public function index()
    {
        $profile     = $this->getProfile();
        $balanceData = $this->calculateBalance($profile);

        $withdrawals = Withdrawal::forMerchant($profile->id)
            ->orderByDesc('requested_at')
            ->paginate(15);

        return view('merchant.withdrawals.index', compact(
            'profile', 'withdrawals', 'balanceData'
        ));
    }

    // ── Store (ajukan withdrawal baru) ────────────────────────────────

    public function store(Request $request)
    {
        $profile     = $this->getProfile();
        $balanceData = $this->calculateBalance($profile);

        $validated = $request->validate([
            'amount'              => [
                'required', 'numeric', 'min:10000',
                'max:' . $balanceData['balance'],
            ],
            'bank_name'           => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_name'   => ['required', 'string', 'max:150'],
        ], [
            'amount.min' => 'Minimum penarikan Rp 10.000.',
            'amount.max' => 'Jumlah melebihi saldo yang tersedia (Rp ' . number_format($balanceData['balance'], 0, ',', '.') . ').',
        ]);

        // Cegah ajukan withdrawal baru jika masih ada yang pending/processing
        $hasPending = Withdrawal::forMerchant($profile->id)
            ->whereIn('status', [Withdrawal::STATUS_PENDING, Withdrawal::STATUS_PROCESSING])
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Anda masih memiliki permintaan penarikan yang sedang diproses. Tunggu hingga selesai sebelum mengajukan yang baru.');
        }

        Withdrawal::create([
            'merchant_id'         => $profile->id,
            'amount'              => $validated['amount'],
            'bank_name'           => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name'   => $validated['bank_account_name'],
            'status'              => Withdrawal::STATUS_PENDING,
            'requested_at'        => now(),
        ]);

        return back()->with('success', 'Permintaan penarikan dana berhasil diajukan. Admin akan memproses dalam 1-3 hari kerja.');
    }
}