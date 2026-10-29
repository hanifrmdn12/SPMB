<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nominal' => ['required', 'numeric', 'min:1'],
            'tanggal_pembayaran' => ['required', 'date', 'before_or_equal:today'],
            'bukti_pembayaran' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nominal' => 'nominal pembayaran',
            'tanggal_pembayaran' => 'tanggal pembayaran',
            'bukti_pembayaran' => 'bukti pembayaran',
        ];
    }
}
