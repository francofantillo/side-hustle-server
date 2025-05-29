<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpEmail extends Notification
{
    use Queueable;

    protected $otp;
    protected $appName;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
        $this->appName = env('APP_NAME', 'Side Hustle');
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('🔐 ' . $this->appName . ' - Your Verification Code')
                    ->greeting('Welcome to ' . $this->appName . '! 👋')
                    ->line('We need to verify your identity to keep your account secure.')
                    ->line('')
                    ->line('**Your verification code is: ' . $this->otp . '**')
                    ->line('')
                    ->line('⏰ This code will expire in 10 minutes.')
                    ->line('🔒 For your security, never share this code with anyone.')
                    ->line('')
                    ->line('If you didn\'t request this verification code, please ignore this email.')
                    ->line('')
                    ->line('Ready to start your hustle? Complete your verification and let\'s get started!')
                    ->salutation('Best regards,  
The ' . $this->appName . ' Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
        ];
    }
}
