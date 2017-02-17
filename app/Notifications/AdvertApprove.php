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
        $message =  (new MailMessage)
                    ->subject(trans('strings.mail_advertApprove_subject'))
                    ->greeting(trans('strings.mail_advertApprove_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_advertApprove_line',['title' => $this->advert->title]))
                    ->action(trans('strings.mail_advertApprove_action'), $this->advert->url)
                    ->line(trans('strings.mail_advertApprove_line2'));

        if($this->advert->getInvoiceFilePath() && file_exists($this->advert->getInvoiceFilePath())){
            $message->attach($this->advert->getInvoiceFilePath(),['as' => trans('strings.pdf_invoice_attachment_name', ['num' => $this->advert->invoice->invoice_number]), 'mime' => 'application/pdf']);
        }

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
