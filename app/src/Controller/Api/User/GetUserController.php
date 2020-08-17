<?php

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use App\Exception\ApiException;
use App\Messenger\Query\GetLoggedUserQuery;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\MessageBus\QueryBus;

/**
 * @Route("/api/get-user", methods={"GET"})
 */
final class GetUserController extends AbstractController
{
    private $queryBus;

    private $translator;

    public function __construct(TranslatorInterface $translator, QueryBus $queryBus)
    {
        $this->translator = $translator;
        $this->queryBus = $queryBus;
    }

    public function __invoke(): ApiResponse
    {
        if (!$this->getUser())
            throw new ApiException($this->translator->trans('user.not_logged'), 400);

        $user = $this->queryBus->query(new GetLoggedUserQuery($this->getUser()));

        return new ApiResponse(
            '',
            '',
            $user,
            [],
            200
        );
    }
}