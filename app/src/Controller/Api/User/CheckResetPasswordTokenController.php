<?php

namespace App\Controller\Api\User;

use App\MessageBus\QueryBus;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Messenger\Query\GetUserByTokenQuery;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/check-reset-password-token", methods={"POST"})
 */
final class CheckResetPasswordTokenController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);

        $this->queryBus->query(new GetUserByTokenQuery($params['token']));

        return new ApiResponse(
            '',
            '',
            null,
            [],
            200
        );
    }


}