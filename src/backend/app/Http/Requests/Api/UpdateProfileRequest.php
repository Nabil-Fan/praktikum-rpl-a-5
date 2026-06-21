<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi input endpoint PUT /api/v1/user/profile.
 * Semua field opsional (PATCH-style) — user boleh update sebagian saja.
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'     => ['sometimes', 'string', 'max:100'],
            'email'    => ['sometimes', 'email', "unique:users,email,{$userId}"],
            'username' => ['sometimes', 'string', 'max:50', "unique:users,username,{$userId}"],
            'phone'    => ['sometimes', 'nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max'        => 'Nama maksimal 100 karakter.',
            'email.email'     => 'Format email tidak valid.',
            'email.unique'    => 'Email sudah dipakai akun lain.',
            'username.unique' => 'Username sudah dipakai akun lain.',
            'phone.max'       => 'Nomor telepon maksimal 20 karakter.',
        ];
    }
}
