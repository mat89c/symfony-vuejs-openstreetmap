<?php

namespace App\Controller\Api\MapPoint;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetAllMapPointsQuery;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/points/", methods={"GET"})
 */
final class GetAllMapPointsController extends AbstractController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $checkedCategories = $request->query->get('checkedCategories');
        $mapBounds = json_decode($request->query->get('mapBounds'), true);
        $page = $request->query->get('page');
        $mapPoints = $this->queryBus->query(new GetAllMapPointsQuery($checkedCategories, $mapBounds, $page));

        return new ApiResponse(
            '',
            '',
            $mapPoints,
            [],
            200
        );
    }
}