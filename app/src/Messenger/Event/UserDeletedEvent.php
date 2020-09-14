<?php

namespace App\Messenger\Event;

class UserDeletedEvent
{
    private $userMapPoints;

    public function __construct(?array $userMapPoints)
    {
        $this->userMapPoints = $userMapPoints;
    }

    public function getUserMapPoints(): ?array
    {
        return $this->userMapPoints;
    }
}