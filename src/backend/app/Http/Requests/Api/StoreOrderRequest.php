<?php

namespace App\Http\Requests\Api;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * Validasi input endpoint POST /api/v1/orders.
 * Memastikan semua field wajib ada sebelum controller memproses order.
 */
class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah diproteksi Sanctum di route
    }

    public function rules(): array
    {
        return [
            'food_listing_id' => ['required', 'integer', 'exists:food_listings,id'],
            'quantity'        => ['required', 'integer', 'min:1', 'max:99'],
            'payment_method'  => ['required', new Enum(PaymentMethod::class)],
        ];
    }

    public function messages(): array
    {
        $validPaymentMethods = implode(', ', array_column(PaymentMethod::cases(), 'value'));

        return [
            'food_listing_id.required' => 'ID menu wajib diisi.',
            'food_listing_id.exists'   => 'Menu tidak ditemukan.',
            'quantity.required'        => 'Jumlah pesanan wajib diisi.',
            'quantity.min'             => 'Jumlah pesanan minimal 1.',
            'quantity.max'             => 'Jumlah pesanan maksimal 99.',
            'payment_method.required'  => 'Metode pembayaran wajib dipilih.',
            'payment_method.*'         => "Metode pembayaran tidak valid. Pilih salah satu: {$validPaymentMethods}.",
        ];
    }
}
