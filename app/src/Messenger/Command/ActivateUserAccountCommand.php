<?php

namespace App\Messenger\Command;

use App\Entity\User;

class ActivateUserAccountCommand
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