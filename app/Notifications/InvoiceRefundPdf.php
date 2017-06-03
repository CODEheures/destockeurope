<?php

namespace App\Notifications;

use App\Advert;
use App\Invoice;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceRefundPdf extends Notification
{

    use Queueable;
    private $role;
    private $invoice;
    private $senderName;
    private $senderMail;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, $senderName, $senderMail, $role)
    {
        $this->invoice = $invoice;
        $this->senderName = $senderName;
        $this->senderMail = $senderMail;
        $this->role = $role;
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
        $message =  $this->role == User::ROLES[User::ROLE_ACCOUNTANT] ?
            (new MailMessage)
                    ->subject(trans('strings.mail_refundInvoice_subject'))
                    ->greeting(trans('strings.mail_refundInvoice_greeting',['username' => $notifiable->name]))
                    ->line(trans('strings.mail_refundInvoice_accountant_line'))
            :(new MailMessage)
                ->subject(trans('strings.mail_refundInvoice_subject'))
                ->greeting(trans('strings.mail_refundInvoice_greeting',['username' => $notifiable->name]))
                ->line(trans('strings.mail_refundInvoice_user_line'));

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
