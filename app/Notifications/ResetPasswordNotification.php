<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Kata Sandi - NesiaStore')
            ->greeting('Halo '.$notifiable->name)
            ->line('Kamu menerima email ini karena kami menerima permintaan reset kata sandi untuk akun NesiaStore Anda.')
            ->action('Reset Kata Sandi', $resetUrl)
            ->line('Link reset kata sandi ini akan kedaluwarsa dalam 60 menit.')
            ->line('Jika Anda tidak meminta reset kata sandi, abaikan email ini. Tidak ada perubahan yang akan dilakukan.')
            ->salutation('Salam hangat, NesiaStore');
    }
}
