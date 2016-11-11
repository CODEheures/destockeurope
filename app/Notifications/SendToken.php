<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendToken extends Notification
{

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

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
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(trans('strings.mail_firstSendToken_subject'))
                    ->greeting(trans('strings.mail_firstSendToken_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_firstSendToken_line',['mail' => $notifiable->email]))
                    ->action(trans('strings.mail_firstSendToken_action'), route('account.confirm', ['id' => $notifiable->id, 'token' => $notifiable->confirmationToken]))
                    ->line(trans('strings.mail_firstSendToken_line2'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
