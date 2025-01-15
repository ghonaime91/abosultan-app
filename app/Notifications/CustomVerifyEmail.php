<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    public static $toMailCallback;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'locale' => app()->getLocale(),
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification())
                ]
        );
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->mailer('smtp')
            ->subject(__('notifications.verify_subject'))
            ->greeting(__('messages.email_greeting').$notifiable->first_name)
            ->line(__('notifications.verify_line_1'))
            ->action(__('notifications.verify_action'), $verificationUrl)
            ->line(__('notifications.verify_line_2'));
    }
}