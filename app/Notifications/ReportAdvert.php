<?php

namespace App\Notifications;

use App\Advert;
use App\Common\CustomerToSellerMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportAdvert extends Notification
{

    use Queueable;

    private $advert;
    private $customerMail;
    private $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Advert $advert, $customerMail, $message)
    {
        $this->advert = $advert;
        $this->customerMail = $customerMail;
        $this->message = $message;
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
     * @return \App\Common\CustomerToSellerMailMessage
     */
    public function toMail($notifiable)
    {

        $mailMessage = new CustomerToSellerMailMessage();
        $mailMessage
            ->subject(trans('strings.mail_report_subject'))
            ->greeting(trans('strings.mail_report_greeting'))
            ->line(trans('strings.mail_report_line',['customermail' => $this->customerMail]))
            ->customerLines($this->message)
            ->action(trans('strings.mail_report_action'), route('advert.show', ['id' => $this->advert->id]));

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
