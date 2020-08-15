<?php

namespace App\Messenger\QueryHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Query\GetLoggedUserQuery;

class GetLoggedUserQueryHandler implements MessageHandlerInterface
{
    public function __invoke(GetLoggedUserQuery $getLoggedUserQuery): array
    {
        $user = $getLoggedUserQuery->getUser();
        return [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'isActive' => $user->getIsActive()
        ];
    }
}