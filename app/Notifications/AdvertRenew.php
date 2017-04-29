<?php

namespace App\Notifications;

use App\Advert;
use App\Common\LocaleUtils;
use App\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdvertRenew extends Notification
{

    use Queueable;
    private $advert;
    private $invoice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Advert $advert, Invoice $invoice)
    {
        $this->advert = $advert;
        $this->invoice = $invoice;
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
                    ->subject(trans('strings.mail_advertRenew_subject'))
                    ->greeting(trans('strings.mail_advertRenew_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_advertRenew_line',[
                        'title' => $this->advert->title,
                        'date' => LocaleUtils::getTransDate($this->advert->ended_at)
                    ]))
                    ->line(trans('strings.mail_advertApprove_line2'));

        if($this->invoice->filePath && file_exists($this->invoice->filePath)){
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
