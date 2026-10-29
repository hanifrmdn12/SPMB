<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAdminRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function create(): View
    {
        return view('admin.login');
    }

    public function store(LoginAdminRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak sesuai.',
            ]);
        }

        $request->session()->regenerate();

        if (! $request->user()->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun ini bukan akun admin.',
            ]);
        }

        return to_route('admin.home');
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return to_route('admin.login')->with('success', 'Anda telah keluar dari akun admin.');
    }
}
