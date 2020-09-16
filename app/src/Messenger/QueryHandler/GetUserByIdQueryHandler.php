<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetUserByIdQuery;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetUserByIdQueryHandler implements MessageHandlerInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetUserByIdQuery $getUserByIdQuery): array
    {
        $id = $getUserByIdQuery->getId();
        $user = $this->userRepository->getUserById($id);

        return $user;
    }
}