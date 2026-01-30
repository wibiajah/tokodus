<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerVerifyEmail extends VerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'customer.verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Selamat Datang di TokoDus!')
            ->greeting('Halo ' . $notifiable->firstname . '!')
            ->line('Terima kasih sudah bergabung dengan **TokoDus** menggunakan akun Google Anda.')
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Username:** ' . $notifiable->username)
            ->action('✅ Verifikasi Email Saya', $verificationUrl)
            ->line('Link verifikasi ini akan kadaluarsa dalam 60 menit.')
            ->line('Jika Anda tidak membuat akun, abaikan email ini.')
            ->salutation('Selamat berbelanja! 🛒');
    }
}