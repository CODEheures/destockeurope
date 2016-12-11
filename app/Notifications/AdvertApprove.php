<?php

namespace App\Notifications;

use App\Advert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdvertApprove extends Notification
{

    use Queueable;
    private $advert;
    private $senderName;
    private $senderMail;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Advert $advert, $senderName, $senderMail)
    {
        $this->advert = $advert;
        $this->senderName = $senderName;
        $this->senderMail = $senderMail;
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
                    ->subject(trans('strings.mail_advertApprove_subject'))
                    ->greeting(trans('strings.mail_advertApprove_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_advertApprove_line',['title' => $this->advert->title]))
                    ->action(trans('strings.mail_advertApprove_action'), route('advert.show', ['id' => $this->advert->id]))
                    ->line(trans('strings.mail_advertApprove_line2'));
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
