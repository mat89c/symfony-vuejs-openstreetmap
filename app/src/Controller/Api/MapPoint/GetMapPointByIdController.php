<?php

namespace App\Controller\Api\MapPoint;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetMapPointByIdQuery;
use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;

/**
 * @Route("/api/point/{id}", methods={"GET"})
 */
final class GetMapPointByIdController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(int $id): ApiResponse
    {
        $mapPoint = $this->queryBus->query(new GetMapPointByIdQuery($id));

        return new ApiResponse(
            '',
            '',
            $mapPoint,
            [],
            200
        );
    }
}