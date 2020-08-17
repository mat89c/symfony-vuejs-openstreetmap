<?php

namespace App\Controller\Api\User;
use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Exception\ApiException;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Messenger\Query\GetUserByTokenQuery;
use App\Messenger\Command\ActivateUserAccountCommand;
use App\MessageBus\QueryBus;

/**
 * @Route("/api/activate-account", methods={"PATCH"})
 */
final class ActivateUserAccountController
{
    private $queryBus;

    private $commandBus;

    private $translator;

    public function __construct(
        QueryBus $queryBus,
        MessageBusInterface $commandBus,
        TranslatorInterface $translator
    )
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);

        if (!isset($params['token']))
            throw new ApiException($this->translator->trans('error.invalid_token'), 400);

        $user = $this->queryBus->query(new GetUserByTokenQuery(($params['token'])));

        $this->commandBus->dispatch(new ActivateUserAccountCommand($user));

        return new ApiResponse(
            $this->translator->trans('user.account_activated'),
            '',
            null,
            [],
            201
        );
    }
}