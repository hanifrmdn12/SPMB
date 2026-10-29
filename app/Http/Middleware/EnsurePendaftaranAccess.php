<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePendaftaranAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pendaftaran = $request->route('pendaftaran');

        if ($pendaftaran === null || ! $request->session()->get("pendaftaran_access.{$pendaftaran->id}")) {
            return redirect()
                ->route('status.index')
                ->withErrors(['nomor_pendaftaran' => 'Masukkan nomor pendaftaran dan verifikasi kode OTP untuk melanjutkan proses.']);
        }

        return $next($request);
    }
}
