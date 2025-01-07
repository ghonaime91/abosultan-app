<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url') . route('password.reset',
         ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()],
          false)
        );

        return (new MailMessage)
            ->subject('إعادة تعيين كلمة المرور')
            ->line('لقد طلبت إعادة تعيين كلمة المرور الخاصة بك.')
            ->action('إعادة تعيين كلمة المرور', $resetUrl)
            ->line('إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذا البريد الإلكتروني.')
            ->salutation('فريق الدعم');
    }
}
