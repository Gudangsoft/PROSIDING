<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $role = $input['role'] ?? 'author';
        $isParticipant = $role === 'participant';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'role' => ['required', 'string', 'in:author,participant'],
            'gender' => ['required', 'in:male,female'],
            'institution' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
        ];

        // Common optional fields for all roles
        $rules['research_interest'] = ['required', 'string', 'max:255'];
        $rules['other_info'] = ['nullable', 'string', 'max:1000'];
        $rules['signature'] = ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'];

        // Check if selected package is free (before validation)
        $isFreePackage = false;
        $packageObj = null;
        if ($isParticipant && isset($input['registration_package_id'])) {
            $packageObj = \App\Models\RegistrationPackage::find((int) $input['registration_package_id']);
            $isFreePackage = $packageObj && $packageObj->is_free;
        }

        // Participant-specific: proof of payment & amount required only for paid packages
        if ($isParticipant && !$isFreePackage) {
            $rules['payment_amount'] = ['required', 'numeric', 'min:1'];
            $rules['proof_of_payment'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];
        }

        Validator::make($input, $rules)->validate();

        $data = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $role,
            'gender' => $input['gender'],
            'institution' => $input['institution'],
            'country' => $input['country'],
            'phone' => $input['phone'],
            'research_interest' => $input['research_interest'] ?? null,
            'other_info' => $input['other_info'] ?? null,
        ];

        if (isset($input['signature']) && $input['signature'] instanceof \Illuminate\Http\UploadedFile) {
            $data['signature'] = $input['signature']->store('signatures', 'public');
        }

        if ($isParticipant && isset($input['proof_of_payment']) && $input['proof_of_payment'] instanceof \Illuminate\Http\UploadedFile) {
            $data['proof_of_payment'] = $input['proof_of_payment']->store('proof-of-payment', 'public');
        }

        $user = User::create($data);

        // Create Payment record for participant
        if ($isParticipant && $isFreePackage) {
            // Free package: auto-create verified payment (no proof required)
            $packageId = isset($input['registration_package_id']) ? (int) $input['registration_package_id'] : null;
            $packageName = $packageObj ? ' — ' . $packageObj->name : '';
            Payment::create([
                'type' => Payment::TYPE_PARTICIPANT,
                'user_id' => $user->id,
                'paper_id' => null,
                'registration_package_id' => $packageId,
                'invoice_number' => Payment::generateInvoiceNumber(),
                'amount' => 0,
                'description' => 'Registrasi Gratis' . $packageName,
                'status' => 'verified',
                'payment_method' => 'Gratis',
                'payment_proof' => null,
                'paid_at' => now(),
            ]);
        } elseif ($isParticipant && !empty($data['proof_of_payment'])) {
            $packageId = isset($input['registration_package_id']) ? (int) $input['registration_package_id'] : null;
            $packageName = '';
            if ($packageId) {
                $pkg = \App\Models\RegistrationPackage::find($packageId);
                $packageName = $pkg ? ' — ' . $pkg->name : '';
            }
            Payment::create([
                'type' => Payment::TYPE_PARTICIPANT,
                'user_id' => $user->id,
                'paper_id' => null,
                'registration_package_id' => $packageId,
                'invoice_number' => Payment::generateInvoiceNumber(),
                'amount' => $input['payment_amount'] ?? 0,
                'description' => 'Pembayaran registrasi partisipan' . $packageName,
                'status' => 'uploaded',
                'payment_proof' => $data['proof_of_payment'],
                'paid_at' => now(),
            ]);
        }

        // Send welcome notification to newly registered user
        Notification::createForUser(
            userId: $user->id,
            type: 'success',
            title: 'Selamat Datang! 🎉',
            message: 'Akun Anda telah berhasil didaftarkan. ' . ($isFreePackage ? 'Paket gratis Anda sudah aktif, selamat bergabung!' : ($isParticipant ? 'Bukti pembayaran Anda sudah diterima dan akan segera diverifikasi.' : 'Silakan login dan mulai submit paper Anda.')),
            actionUrl: url('/dashboard'),
            actionText: 'Ke Dashboard'
        );

        // Send welcome email
        try {
            Mail::to($user->email)->send(
                new WelcomeMail($user->name, $role, url('/dashboard'))
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        return $user;
    }
}
