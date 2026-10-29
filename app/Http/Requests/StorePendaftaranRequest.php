<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'digits:16', Rule::unique('calon_siswa', 'nik')],
            'nisn' => ['nullable', 'digits:10', Rule::unique('calon_siswa', 'nisn')],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'agama' => ['required', 'string', 'max:50'],
            'alamat' => ['required', 'string', 'max:1000'],
            'nama_wali' => ['required', 'string', 'max:255'],
            'nomor_whatsapp' => ['required', 'string', 'max:20', 'regex:/^(\\+62|62|0)8[0-9]{7,12}$/'],
            'tahun_ajaran' => ['required', 'regex:/^20\\d{2}\/20\\d{2}$/'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap calon siswa',
            'nama_wali' => 'nama orang tua/wali',
            'nomor_whatsapp' => 'nomor WhatsApp',
            'tahun_ajaran' => 'tahun ajaran',
        ];
    }
}
