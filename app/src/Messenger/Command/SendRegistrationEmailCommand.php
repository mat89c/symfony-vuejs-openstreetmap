<?php

namespace App\Messenger\Command;

use App\Entity\User;

class SendRegistrationEmailCommand
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}