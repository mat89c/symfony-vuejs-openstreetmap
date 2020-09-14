<?php

namespace App\Controller\Admin\MapPoint;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetAllMapPointsQuery;
use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/points", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class GetAllMapPointsController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $page = $request->query->get('page');
        $status = $request->query->get('status');
        $mapPoints = $this->queryBus->query(new GetAllMapPointsQuery($page, $status));
        return new ApiResponse(
            '',
            '',
            $mapPoints,
            [],
            200
        );
    }
}