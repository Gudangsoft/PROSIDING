<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Notification;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        // Send notification after password reset
        Notification::createForUser(
            userId: $user->id,
            type: 'success',
            title: 'Password Berhasil Direset 🔒',
            message: 'Password akun Anda telah berhasil diubah. Gunakan password baru untuk login selanjutnya.',
            actionUrl: route('login'),
            actionText: 'Login Sekarang'
        );

        // Send password reset email
        try {
            Mail::to($user->email)->send(
                new PasswordResetMail($user->name, route('login'))
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
        }
    }
}
