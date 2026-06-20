<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UploadPaymentProofRequest;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * PaymentController menangani upload bukti pembayaran dari user mobile.
 * Setelah upload, merchant akan konfirmasi secara manual lewat portal web.
 */
class PaymentController extends Controller
{
    /**
     * Upload bukti pembayaran untuk order tertentu.
     * POST /api/v1/orders/{id}/payment-proof
     */
    public function uploadProof(UploadPaymentProofRequest $request, int $orderId): JsonResponse
    {
        // Pastikan order milik user yang sedang login
        $order = Order::where('id', $orderId)
                      ->where('user_id', $request->user()->id)
                      ->first();

        if (! $order) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        // Hanya order yang masih pending yang bisa upload bukti
        if (! $order->isPending()) {
            return response()->json([
                'message' => 'Bukti pembayaran hanya bisa diupload untuk pesanan yang masih menunggu konfirmasi.',
            ], 422);
        }

        // Ambil payment terbaru milik order ini
        $payment = $order->payments()->latest()->first();

        if (! $payment) {
            return response()->json([
                'message' => 'Data pembayaran tidak ditemukan.',
            ], 404);
        }

        // Hapus file lama jika ada (misalnya user re-upload)
        if ($payment->payment_proof_url) {
            $oldPath = str_replace('/storage/', 'public/', $payment->payment_proof_url);
            Storage::delete($oldPath);
        }

        // Simpan file baru ke storage/app/public/payment-proofs/
        $file     = $request->file('payment_proof');
        $fileName = 'proof_' . $order->id . '_' . time() . '.' . $file->extension();
        $path     = $file->storeAs('public/payment-proofs', $fileName);

        // URL yang bisa diakses publik
        $publicUrl = '/storage/payment-proofs/' . $fileName;

        // Update payment_proof_url di tabel payments
        $payment->update([
            'payment_proof_url' => $publicUrl,
        ]);

        return response()->json([
            'message'           => 'Bukti pembayaran berhasil diupload.',
            'payment_proof_url' => $publicUrl,
        ]);
    }
}
