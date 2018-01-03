<?php

namespace App\Notifications;

use App\Common\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AlertWaitingsAdverts extends Notification
{

    use Queueable;
    private $waitingsCount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($waitingsCount = 0)
    {
        $this->waitingsCount = $waitingsCount;
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
        $mailMessage = new CustomMailMessage();
        $mailMessage
            ->subject(trans('strings.mail_alertWaitingsAdverts_subject'))
            ->greeting(trans('strings.mail_alertWaitingsAdverts_greeting',['validatorName' => ucfirst($notifiable->name)]))
            ->line(trans('strings.mail_alertWaitingsAdverts_line',['waintingsCount' => $this->waitingsCount]))
            ->action(trans('strings.mail_alertWaitingsAdverts_action'), route('advert.toApprove'));
        return $mailMessage;
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
