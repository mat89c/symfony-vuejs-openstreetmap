<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\SearchUserByIdOrEmailQuery;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SearchUserByIdOrEmailQueryHandler implements MessageHandlerInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(SearchUserByIdOrEmailQuery $searchUserByIdOrEmailQuery): array
    {
        $value = $searchUserByIdOrEmailQuery->getValue();
        $users = $this->userRepository->searchUserByIdOrEmail($value);

        return $users;
    }
}