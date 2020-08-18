<?php

namespace App\Controller\Api\User;

use App\MessageBus\QueryBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Messenger\Query\GetUserByEmailQuery;
use App\Messenger\Command\SendResetPasswordEmailCommand;
use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/send-reset-password-email", methods={"POST"})
 */
final class SendResetPasswordEmailController
{
    private $queryBus;

    private $commandBus;

    private $translator;

    public function __construct(
        QueryBus $queryBus,
        MessageBusInterface $commandBus,
        TranslatorInterface $translator)
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);

        $user = $this->queryBus->query(new GetUserByEmailQuery($params['email']));

        $this->commandBus->dispatch(new SendResetPasswordEmailCommand($user));

        return new ApiResponse(
            $this->translator->trans('reset_password.email_sended.message'),
            $this->translator->trans('reset_password.email_sended.title'),
            null,
            [],
            200
        );
    }
}