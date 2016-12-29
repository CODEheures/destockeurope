<?php

namespace App\Notifications;

use App\Advert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlertObsoleteAdvert extends Notification
{

    use Queueable;

    private $advert;
    private $days;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Advert $advert, $days)
    {
        $this->advert = $advert;
        $this->days = $days;
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
        $mail = new MailMessage;

        if($this->days>1){
            $mail->subject(trans('strings.mail_alertObsoleteAdvert_subject'))
                ->greeting(trans('strings.mail_alertObsoleteAdvert_greeting',['username' => $notifiable->name]))
                ->line(trans('strings.mail_alertObsoleteAdvert_line',['title' => $this->advert->title, 'days'=> $this->days]));
        } elseif ($this->days==1) {
            $mail->subject(trans('strings.mail_alertObsoleteAdvert_subject_lastday'))
                ->greeting(trans('strings.mail_alertObsoleteAdvert_greeting',['username' => $notifiable->name]))
                ->line(trans('strings.mail_alertObsoleteAdvert_line_lastday',['title' => $this->advert->title]));
        } else {
            $mail->subject(trans('strings.mail_alertObsoleteAdvert_subject_afterday'))
                ->greeting(trans('strings.mail_alertObsoleteAdvert_greeting',['username' => $notifiable->name]))
                ->line(trans('strings.mail_alertObsoleteAdvert_line_afterdays',['title' => $this->advert->title]));
        }

        $mail->action(trans('strings.mail_alertObsoleteAdvert_action'), route('mines'))
            ->line(trans('strings.mail_alertObsoleteAdvert_line2'));

        return $mail;
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
