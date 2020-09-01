<?php

namespace App\Messenger\QueryHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Query\GetLoggedUserQuery;
use Namshi\JOSE\JWS;
use Symfony\Component\Security\Core\Security;

class GetLoggedUserQueryHandler implements MessageHandlerInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(GetLoggedUserQuery $getLoggedUserQuery): array
    {
        $user = $getLoggedUserQuery->getUser();
        $token = $this->security->getToken();
        $jws = JWS::load($token->getCredentials());
        $expiriesDate = $jws->getPayload()['exp'];

        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'isActive' => $user->getIsActive(),
            'expiriesDate' => $expiriesDate
        ];
    }
}