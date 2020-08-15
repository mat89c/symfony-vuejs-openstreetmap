<?php

namespace App\Messenger\QueryHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Query\GetUserByTokenQuery;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Exception\ApiException;
use Symfony\Contracts\Translation\TranslatorInterface;

class GetUserByTokenQueryHandler implements MessageHandlerInterface
{
    private $userRepository;

    private $translator;

    public function __construct(UserRepository $userRepository, TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function __invoke(GetUserByTokenQuery $getUserByTokenQuery): User
    {
        $user = $this->userRepository->findOneBy(['token' => $getUserByTokenQuery->getToken()]);

        if (!$user)
            throw new ApiException($this->translator->trans('user not found'), 404);

        return $user;
    }
}