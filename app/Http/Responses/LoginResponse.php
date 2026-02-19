<?php

namespace App\Http\Responses;

use App\Models\Payment;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\JsonResponse;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        // Participant: redirect to payment page if not yet verified
        if ($user->role === 'participant') {
            $hasVerifiedPayment = Payment::where('user_id', $user->id)
                ->where('type', Payment::TYPE_PARTICIPANT)
                ->where('status', 'verified')
                ->exists();

            if (!$hasVerifiedPayment) {
                return $request->wantsJson()
                    ? new JsonResponse(['two_factor' => false], 200)
                    : redirect()->route('participant.payment')
                        ->with('info', 'Akun Anda belum aktif. Silakan upload bukti pembayaran untuk diverifikasi admin.');
            }
        }

        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false], 200)
            : redirect()->intended('/dashboard');
    }
}
