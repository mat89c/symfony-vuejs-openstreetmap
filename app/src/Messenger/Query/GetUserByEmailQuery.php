<?php

namespace App\Messenger\Query;

class GetUserByEmailQuery
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
