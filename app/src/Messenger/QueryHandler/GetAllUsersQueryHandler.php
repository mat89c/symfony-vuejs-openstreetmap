<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetAllUsersQuery;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetAllUsersQueryHandler implements MessageHandlerInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetAllUsersQuery $getAllUsersQuery): array
    {
        $page = $getAllUsersQuery->getPage();
        $status = $getAllUsersQuery->getStatus();

        $users = $this->userRepository->getAllUsers($page, $status);
        return $users;
    }
}