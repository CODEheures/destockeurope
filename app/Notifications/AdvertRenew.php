<?php

namespace App\Notifications;

use App\Advert;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdvertRenew extends Notification
{

    use Queueable;
    private $advert;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
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
                    ->subject(trans('strings.mail_advertRenew_subject'))
                    ->greeting(trans('strings.mail_advertRenew_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_advertRenew_line',[
                        'title' => $this->advert->title,
                        'date' => Carbon::parse($this->advert->online_at)->toDateTimeString(),
                        'days' => env('ADVERT_LIFE_TIME')
                    ]))
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
