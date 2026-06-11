<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WithdrawalController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = Withdrawal::with(['merchant.user'])
            ->orderByDesc('requested_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('merchant', function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $withdrawals = $query->paginate(20)->withQueryString();

        $counts = [
            'all'        => Withdrawal::count(),
            'pending'    => Withdrawal::where('status', Withdrawal::STATUS_PENDING)->count(),
            'processing' => Withdrawal::where('status', Withdrawal::STATUS_PROCESSING)->count(),
            'completed'  => Withdrawal::where('status', Withdrawal::STATUS_COMPLETED)->count(),
            'rejected'   => Withdrawal::where('status', Withdrawal::STATUS_REJECTED)->count(),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'status', 'search', 'counts'));
    }

    // ── Show ──────────────────────────────────────────────────────────

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load(['merchant.user', 'admin']);
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    // ── Approve (pending → processing) ────────────────────────────────

    public function approve(Request $request, Withdrawal $withdrawal)
    {
        if (!$withdrawal->isPending()) {
            return back()->with('error', 'Hanya withdrawal dengan status pending yang bisa disetujui.');
        }

        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $withdrawal->update([
            'status'       => Withdrawal::STATUS_PROCESSING,
            'admin_id'     => Auth::id(),
            'admin_notes'  => $request->admin_notes,
            'processed_at' => now(),
        ]);

        return back()->with('success', "Withdrawal #{$withdrawal->id} disetujui dan sedang diproses.");
    }

    // ── Complete + upload bukti transfer ──────────────────────────────

    public function complete(Request $request, Withdrawal $withdrawal)
    {
        if (!$withdrawal->isProcessing()) {
            return back()->with('error', 'Hanya withdrawal yang sedang diproses yang bisa diselesaikan.');
        }

        $request->validate([
            'transfer_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'admin_notes'    => ['nullable', 'string', 'max:500'],
        ], [
            'transfer_proof.required' => 'Bukti transfer wajib diunggah.',
        ]);

        $proofUrl = $request->file('transfer_proof')
            ->store('withdrawal-proofs', 'public');

        $withdrawal->update([
            'status'             => Withdrawal::STATUS_COMPLETED,
            'transfer_proof_url' => $proofUrl,
            'admin_notes'        => $request->admin_notes ?? $withdrawal->admin_notes,
            'completed_at'       => now(),
        ]);

        return back()->with('success', "Withdrawal #{$withdrawal->id} selesai. Bukti transfer berhasil diunggah.");
    }

    // ── Reject ────────────────────────────────────────────────────────

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if (!$withdrawal->isPending() && !$withdrawal->isProcessing()) {
            return back()->with('error', 'Withdrawal ini tidak bisa ditolak.');
        }

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:500'],
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $withdrawal->update([
            'status'      => Withdrawal::STATUS_REJECTED,
            'admin_id'    => Auth::id(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', "Withdrawal #{$withdrawal->id} ditolak.");
    }
}