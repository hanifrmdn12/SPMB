<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WhatsAppOtpSender
{
    public function send(string $nomorWhatsApp, string $kode): void
    {
        if (config('services.whatsapp.driver') === 'log') {
            Log::info('OTP pendaftaran (lokal)', ['nomor_whatsapp' => $nomorWhatsApp, 'kode_otp' => $kode]);
            return;
        }

        $url = config('services.whatsapp.url');
        $token = config('services.whatsapp.token');
        $template = config('services.whatsapp.template');

        if (! $url || ! $token || ! $template) {
            throw new RuntimeException('Layanan WhatsApp OTP belum dikonfigurasi.');
        }

        $response = Http::withToken($token)->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $this->normalisasiNomor($nomorWhatsApp),
            'type' => 'template',
            'template' => [
                'name' => $template,
                'language' => ['code' => config('services.whatsapp.language', 'id')],
                'components' => [[
                    'type' => 'body',
                    'parameters' => [['type' => 'text', 'text' => $kode]],
                ]],
            ],
        ]);

        if ($response->failed()) {
            Log::warning('Pengiriman WhatsApp OTP gagal', ['status' => $response->status()]);
            throw new RuntimeException('Kode OTP belum dapat dikirim. Silakan coba lagi.');
        }
    }

    private function normalisasiNomor(string $nomor): string
    {
        $nomor = preg_replace('/\D/', '', $nomor);

        return str_starts_with($nomor, '0') ? '62'.substr($nomor, 1) : $nomor;
    }
}
