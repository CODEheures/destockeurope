<?php

namespace App\Common;

use Illuminate\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage;

class CustomMailMessage extends MailMessage
{

    public $customMessage = [];
    public $contact = [];

    /**
     * @param $message
     * @return $this
     */
    public function customLines($message) {
        $this->customMessage = preg_split("/\r\n|\n|\r/", $message);
        return $this;
    }


    /**
     * @param $contact
     * @return $this
     */
    public function customContact($contact){
        $this->contact[] = $contact;
        return $this;
    }

    public function with($line)
    {
        if ($line instanceof Action) {
            $this->action($line->text, $line->url);
        } elseif (! $this->actionText && ! $this->customMessage) {
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
            'customMessage' => $this->customMessage,
            'customContact' => $this->contact
        ];
    }


}