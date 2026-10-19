<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CandidatePasswordReset extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage())
            ->subject('Atur ulang kata sandi akun kandidat | ' . config('app.name', 'PT Rapid Plast Indonesia'))
            ->view([
                'html' => 'emails.auth.action',
                'text' => 'emails.auth.action-text',
            ], [
                'brand' => config('app.name', 'PT Rapid Plast Indonesia'),
                'eyebrow' => 'KEAMANAN AKUN',
                'heading' => 'Atur ulang kata sandi',
                'name' => $notifiable->name ?: 'Kandidat',
                'intro' => 'Kami menerima permintaan untuk membuat kata sandi baru bagi akun Anda.',
                'buttonLabel' => 'Buat Kata Sandi Baru',
                'actionUrl' => $this->resetUrl($notifiable),
                'expiry' => 'Tautan ini berlaku selama ' . $minutes . ' menit dan hanya dapat digunakan satu kali.',
                'securityNote' => 'Jika Anda tidak meminta perubahan kata sandi, abaikan email ini. Kata sandi Anda tidak akan berubah.',
            ]);
    }
}
