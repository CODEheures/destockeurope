<?php

namespace App\Notifications;

use App\Advert;
use App\Common\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerContactIntermediary extends Notification
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
     * @return \App\Common\CustomMailMessage
     */
    public function toMail($notifiable)
    {

        $mailMessage = new CustomMailMessage();
        $mailMessage
            ->subject(trans('strings.mail_customerToDelegation_subject'))
            ->greeting(trans('strings.mail_customerToDelegation_greeting',['adminname' => ucfirst($notifiable->name)]))
            ->line(trans('strings.mail_customerToDelegation_line',['customername' => ucfirst($this->customerName), 'title' => ucfirst($this->advert->title)]))
            ->action(trans('strings.mail_customerToDelegation_action'), $this->advert->url)
            ->customLines($this->message)
            ->customContact(trans('strings.mail_customerToDelegation_line2', ['customername' => ucfirst($this->customerName)]))
            ->customContact(trans('strings.mail_customerToDelegation_compagnyName', ['compagnyName' => $this->customerCompagnyName]))
            ->customContact(trans('strings.mail_customerToDelegation_mail', ['mail' => $this->customerMail]))
            ->customContact(trans('strings.mail_customerToDelegation_phone', ['phone' => $this->customerPhone]))
            ->line(trans('strings.mail_customerToDelegation_line3'))
            ->line(trans('strings.mail_customerToDelegation_compagnyName', ['compagnyName' => $this->advert->user->compagnyName]))
            ->line(trans('strings.mail_customerToDelegation_name', ['name' => $this->advert->user->name]))
            ->line(trans('strings.mail_customerToDelegation_mail', ['mail' => $this->advert->user->email]))
            ->line(trans('strings.mail_customerToDelegation_phone', ['phone' => $this->advert->user->phone]))
            ->line(trans('strings.mail_customerToDelegation_vat', ['vat' => $this->advert->user->registrationNumber]))
            ->line(trans('strings.mail_customerToDelegation_address', ['address' => json_decode($this->advert->user->geoloc)[0]->formatted_address]));


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
