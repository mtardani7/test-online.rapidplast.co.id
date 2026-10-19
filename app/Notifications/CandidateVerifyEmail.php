<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CandidateVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Verifikasi email akun kandidat | ' . config('app.name', 'PT Rapid Plast Indonesia'))
            ->view([
                'html' => 'emails.auth.action',
                'text' => 'emails.auth.action-text',
            ], [
                'brand' => config('app.name', 'PT Rapid Plast Indonesia'),
                'eyebrow' => 'AKUN KANDIDAT',
                'heading' => 'Verifikasi alamat email Anda',
                'name' => $notifiable->name ?: 'Kandidat',
                'intro' => 'Satu langkah lagi untuk mengaktifkan akun dan melanjutkan proses seleksi Anda.',
                'buttonLabel' => 'Verifikasi Email',
                'actionUrl' => $this->verificationUrl($notifiable),
                'expiry' => 'Tautan ini berlaku selama ' . config('auth.verification.expire', 60) . ' menit.',
                'securityNote' => 'Jika Anda tidak membuat akun di sistem rekrutmen kami, abaikan email ini. Akun Anda tidak akan diaktifkan.',
            ]);
    }
}
