<?php

namespace App\Controller\Admin\MapPoint;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetMapPointByIdQuery;
use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/point/{id}", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
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