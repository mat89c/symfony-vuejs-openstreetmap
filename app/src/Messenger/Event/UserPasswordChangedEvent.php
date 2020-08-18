<?php

namespace App\Messenger\Event;

use App\Entity\User;

class UserPasswordChangedEvent
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