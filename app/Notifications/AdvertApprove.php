<?php

namespace App\Notifications;

use App\Advert;
use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdvertApprove extends Notification
{

    use Queueable;
    private $advert;
    private $invoice;
    private $senderName;
    private $senderMail;
    private $state;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Advert $advert, $state, Invoice $invoice=null, $senderName, $senderMail)
    {
        $this->advert = $advert;
        $this->state = $state;
        $this->invoice = $invoice;
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
        $message =
            $this->state ==  Invoice::STATE_CREATION ?
                (new MailMessage)
                    ->subject(trans('strings.mail_advertApprove_subject'))
                    ->greeting(trans('strings.mail_advertApprove_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_advertApprove_line',['title' => $this->advert->title]))
                    ->action(trans('strings.mail_advertApprove_action'), $this->advert->url)
                    ->line(trans('strings.mail_advertApprove_line2'))
                :(new MailMessage)
                    ->subject(trans('strings.mail_advertApproveEdit_subject'))
                    ->greeting(trans('strings.mail_advertApproveEdit_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_advertApproveEdit_line',['title' => $this->advert->title]))
                    ->action(trans('strings.mail_advertApprove_action'), $this->advert->url)
                    ->line(trans('strings.mail_advertApprove_line2'));

        if($this->invoice && $this->invoice->filePath && file_exists($this->invoice->filePath)){
            $message->attach($this->invoice->filePath,['as' => trans('strings.pdf_invoice_attachment_name', ['num' => $this->invoice->invoice_number]), 'mime' => 'application/pdf']);
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
