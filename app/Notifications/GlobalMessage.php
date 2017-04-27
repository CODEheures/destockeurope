<?php

namespace App\Notifications;

use App\Advert;
use App\Common\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class GlobalMessage extends Notification
{

    use Queueable;

    private $customerMail;
    private $name;
    private $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($customerMail, $name, $message)
    {
        $this->customerMail = $customerMail;
        $this->name = $name && $name !='' ? $name : '-';
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
     * @return \App\Common\CustomMailMessage
     */
    public function toMail($notifiable)
    {

        $mailMessage = new CustomMailMessage();
        $mailMessage
            ->subject(trans('strings.mail_global_subject'))
            ->greeting(trans('strings.mail_global_greeting'))
            ->line(trans('strings.mail_global_line',['customermail' => $this->customerMail, 'name' => $this->name]))
            ->customLines($this->message);

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
