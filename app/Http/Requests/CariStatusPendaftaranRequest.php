<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CariStatusPendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_pendaftaran' => ['required', 'string', 'max:50'],
            'pin_akses' => ['required', 'digits:6'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nomor_pendaftaran' => 'nomor pendaftaran',
            'pin_akses' => 'PIN akses',
        ];
    }
}
