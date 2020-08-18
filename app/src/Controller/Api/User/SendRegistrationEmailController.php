<?php

namespace App\Controller\Api\User;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\MessageBus\QueryBus;
use App\Messenger\Query\GetUserByEmailQuery;
use App\Messenger\Command\SendRegistrationEmailCommand;
use App\Response\ApiResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/send-registration-email", methods={"POST"})
 */
final class SendRegistrationEmailController
{
    private $queryBus;

    private $commandBus;

    private $translator;

    public function __construct(MessageBusInterface $commandBus, QueryBus $queryBus, TranslatorInterface $translator)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);

        $user = $this->queryBus->query(new GetUserByEmailQuery($params['email']));

        $this->commandBus->dispatch(new SendRegistrationEmailCommand($user));

        return new ApiResponse(
            $this->translator->trans('registration.email_sended.message'),
            $this->translator->trans('registration.email_sended.title'),
            null,
            [],
            200
        );
    }
}