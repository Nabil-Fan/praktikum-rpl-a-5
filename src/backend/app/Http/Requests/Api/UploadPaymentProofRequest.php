<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi input endpoint POST /api/v1/orders/{id}/payment-proof.
 * Memastikan file yang diupload adalah gambar valid dengan ukuran wajar.
 */
class UploadPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah diproteksi Sanctum di route
    }

    public function rules(): array
    {
        return [
            'payment_proof' => [
                'required',
                'file',
                'image',                // harus berupa gambar
                'mimes:jpg,jpeg,png',   // hanya format ini
                'max:2048',             // maksimal 2MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_proof.required' => 'File bukti pembayaran wajib diupload.',
            'payment_proof.file'     => 'Upload harus berupa file.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.mimes'    => 'Format gambar harus jpg, jpeg, atau png.',
            'payment_proof.max'      => 'Ukuran file maksimal 2MB.',
        ];
    }
}
