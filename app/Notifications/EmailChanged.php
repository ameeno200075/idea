<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected User $user,
        protected string $originalEmail
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Force this notification to always be sent to the ORIGINAL email
     * address, not the new one currently on the user's account.
     */
    public function routeNotificationForMail(object $notifiable): string
    {
        return $this->originalEmail;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Security Alert: Email Address Changed')
            ->greeting("Hello {$this->user->name},")
            ->line("We are writing to inform you that your email address ({$this->originalEmail}) has been removed from your account and updated to a new address.")
            ->line('If you made this change, no further action is required.')
            ->line('If you did not request this change, please secure your account immediately or contact support.')
            ->action('Visit Website', url('/profile'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
