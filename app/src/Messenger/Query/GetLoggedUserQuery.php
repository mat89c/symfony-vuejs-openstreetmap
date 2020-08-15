<?php

namespace App\Messenger\Query;

use App\Entity\User;

class GetLoggedUserQuery
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