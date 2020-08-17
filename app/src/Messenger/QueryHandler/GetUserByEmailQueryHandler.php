<?php

namespace App\Messenger\QueryHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Query\GetUserByEmailQuery;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Exception\ApiException;

class GetUserByEmailQueryHandler implements MessageHandlerInterface
{
    private $userRepository;

    private $translator;

    public function __construct(UserRepository $userRepository, TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function __invoke(GetUserByEmailQuery $getUserByEmailQuery): User
    {
        $user = $this->userRepository->findOneBy(['email' => $getUserByEmailQuery->getEmail()]);

        if (!$user)
            throw new ApiException($this->translator->trans('user.not_found'), 404);

        return $user;
    }
}