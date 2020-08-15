<?php

namespace App\Controller\Api\User;
use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Exception\ApiException;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Messenger\Query\GetUserByTokenQuery;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use App\Messenger\Command\ActivateUserAccountCommand;

/**
 * @Route("/api/activate-account", methods={"PATCH"})
 */
class ActivateUserAccountController
{
    private $queryBus;

    private $commandBus;

    private $translator;

    public function __construct(
        MessageBusInterface $queryBus,
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
            throw new ApiException($this->translator->trans('invalid token'), 400);

        $envelope = $this->queryBus->dispatch(new GetUserByTokenQuery(($params['token'])));
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $user = $handled->getResult();

        $this->commandBus->dispatch(new ActivateUserAccountCommand($user));

        return new ApiResponse(
            $this->translator->trans('user account activated'),
            '',
            null,
            [],
            201
        );
    }
}