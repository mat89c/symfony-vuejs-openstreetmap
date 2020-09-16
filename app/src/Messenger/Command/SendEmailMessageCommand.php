<?php

namespace App\Messenger\Command;

class SendEmailMessageCommand
{
    private $message;

    public function __construct(array $message)
    {
        $this->message = $message;
    }

    public function getMessage(): array
    {
        return $this->message;
    }
}