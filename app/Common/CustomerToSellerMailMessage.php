<?php

namespace App\Common;

use Illuminate\Notifications\Messages\MailMessage;

class CustomerToSellerMailMessage extends MailMessage
{

    public $customerMessage = [];
    public $contact = [];

    /**
     * @param $message
     * @return $this
     */
    public function customerLines($message) {
        $this->customerMessage = preg_split("/\r\n|\n|\r/", $message);
        return $this;
    }


    /**
     * @param $contact
     * @return $this
     */
    public function customerContact($contact){
        $this->contact[] = $contact;
        return $this;
    }

    public function with($line)
    {
        if ($line instanceof Action) {
            $this->action($line->text, $line->url);
        } elseif (! $this->actionText && ! $this->customerMessage) {
            $this->introLines[] = $this->formatLine($line);
        } else {
            $this->outroLines[] = $this->formatLine($line);
        }

        return $this;
    }


    public function toArray()
    {
        return [
            'level' => $this->level,
            'subject' => $this->subject,
            'greeting' => $this->greeting,
            'introLines' => $this->introLines,
            'outroLines' => $this->outroLines,
            'actionText' => $this->actionText,
            'actionUrl' => $this->actionUrl,
            'customerMessage' => $this->customerMessage,
            'customerContact' => $this->contact
        ];
    }


}