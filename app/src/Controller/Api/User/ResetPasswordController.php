<?php

namespace App\Controller\Api\User;

use App\MessageBus\QueryBus;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Messenger\Query\GetUserByTokenQuery;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Messenger\Command\ResetUserPasswordCommand;
use App\Response\ApiResponse;
use App\Messenger\Query\GetUserByEmailQuery;

/**
 * @Route("/api/reset-password", methods="PATCH")
 */
final class ResetPasswordController
{
    private $queryBus;

    private $commandBus;

    private $translator;

    private $passwordEncoder;

    public function __construct(
        QueryBus $queryBus,
        MessageBusInterface $commandBus,
        TranslatorInterface $translator,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);

        $user = $this->queryBus->query(new GetUserByTokenQuery($params['token']));
        $password = $this->passwordEncoder->encodePassword($user, $params['password']);
        $user->setPassword($password);
        $user->setToken('');

        $this->commandBus->dispatch(new ResetUserPasswordCommand($user));

        return new ApiResponse(
            $this->translator->trans('reset_password.message'),
            $this->translator->trans('reset_password.title'),
            null,
            [],
            201,
        );
    }
}