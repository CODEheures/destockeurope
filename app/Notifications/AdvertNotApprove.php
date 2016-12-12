<?php

namespace App\Notifications;

use App\Advert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdvertNotApprove extends Notification
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
        $message = (new MailMessage)
                    ->subject(trans('strings.mail_advertNotApprove_subject'))
                    ->greeting(trans('strings.mail_advertNotApprove_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_advertNotApprove_line',['title' => $this->advert->title]));

        if($this->advert->invoice && $this->advert->invoice->voidId){
            $message->line(trans('strings.mail_advertNotApprove_line_voidPayment'));
        }

        $message->line(trans('strings.mail_advertNotApprove_line2'));

        return $message;
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