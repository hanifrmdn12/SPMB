<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dokumen' => ['required', 'array', 'min:1'],
            'dokumen.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'dokumen' => 'berkas dokumen',
            'dokumen.*' => 'berkas dokumen',
        ];
    }
}
