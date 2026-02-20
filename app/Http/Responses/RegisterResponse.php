<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        // Free participant: payment already verified – keep logged in, go to dashboard
        if ($user && $user->role === 'participant') {
            $hasVerifiedPayment = $user->payments()
                ->where('status', 'verified')
                ->exists();

            if ($hasVerifiedPayment) {
                return redirect()->route('dashboard')->with(
                    'status',
                    'Registrasi gratis berhasil! Selamat datang, ' . $user->name . '!'
                );
            }

            // Paid participant: log out and wait for admin verification
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'status',
                'Registrasi berhasil! Akun Anda akan aktif setelah pembayaran diverifikasi oleh admin.'
            );
        }

        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false], 200)
            : redirect()->intended('/dashboard');
    }
}
