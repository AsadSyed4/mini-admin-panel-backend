<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewCompanyNotification extends Notification
{
     /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->line('A new company has been added:')
        ->line('Name: ' . $notifiable->name)
        ->line('Email: ' . $notifiable->email)
        ->line('Website: ' . $notifiable->website)
        ->line('Thank you for using our application!');
    }

}
