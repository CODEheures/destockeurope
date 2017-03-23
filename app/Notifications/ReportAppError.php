<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportAppError extends Notification
{

    use Queueable;

    private $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $senderName, $senderMail)
    {
        $this->message = $message;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {

        $mailMessage = new MailMessage();
        $mailMessage
            ->subject(trans('strings.mail_apperror_subject'))
            ->greeting(trans('strings.mail_report_greeting'))
            ->line($this->message);

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
