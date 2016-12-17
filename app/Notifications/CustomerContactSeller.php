<?php

namespace App\Notifications;

use App\Advert;
use App\Common\CustomerToSellerMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerContactSeller extends Notification
{

    use Queueable;

    private $advert;
    private $customerName;
    private $customerMail;
    private $customerPhone;
    private $customerCompagnyName;
    private $message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Advert $advert, $customerName, $customerMail, $message, $customerPhone, $customerCompagnyName)
    {
        $this->advert = $advert;
        $this->customerName = $customerName;
        $this->customerMail = $customerMail;
        $this->message = $message;
        $this->customerPhone = $customerPhone;
        $this->customerCompagnyName = $customerCompagnyName;
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
            ->subject(trans('strings.mail_customerToSeller_subject', ['title' => ucfirst($this->advert->title)]))
            ->greeting(trans('strings.mail_customerToSeller_greeting',['sellername' => ucfirst($notifiable->name)]))
            ->line(trans('strings.mail_customerToSeller_line',['customername' => ucfirst($this->customerName), 'title' => ucfirst($this->advert->title)]))
            ->customerLines($this->message)
            ->customerContact(trans('strings.mail_customerToSeller_line2', ['customername' => ucfirst($this->customerName)]))
            ->customerContact(trans('strings.mail_customerToSeller_comapgnyName', ['compagnyName' => $this->customerCompagnyName]))
            ->customerContact(trans('strings.mail_customerToSeller_mail', ['mail' => $this->customerMail]))
            ->customerContact(trans('strings.mail_customerToSeller_phone', ['phone' => $this->customerPhone]))
            ->line(trans('strings.mail_customerToSeller_line3'));

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
