<?php

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use App\Exception\ApiException;
use App\Messenger\Query\GetLoggedUserQuery;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * @Route("/api/get-user", methods={"GET"})
 */
final class GetUserController extends AbstractController
{
    private $queryBus;

    private $translator;

    public function __construct(TranslatorInterface $translator, MessageBusInterface $queryBus)
    {
        $this->translator = $translator;
        $this->queryBus = $queryBus;
    }

    public function __invoke(): ApiResponse
    {
        if (!$this->getUser())
            throw new ApiException($this->translator->trans('user.not_logged'), 400);

        $user = $this->getUser();
        $envelope = $this->queryBus->dispatch(new GetLoggedUserQuery($user));
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);
        $user = $handled->getResult();

        return new ApiResponse(
            '',
            '',
            $user,
            [],
            200
        );
    }
}