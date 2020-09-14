<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetAllMapPointsQuery;
use App\Repository\MapPointRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetAllMapPointsQueryHandler implements MessageHandlerInterface
{
    private $mapPointRepository;

    public function __construct(MapPointRepository $mapPointRepository)
    {
        $this->mapPointRepository = $mapPointRepository;
    }

    public function __invoke(GetAllMapPointsQuery $getAllMapPointsQuery): array
    {
        $page = $getAllMapPointsQuery->getPage();
        $status = $getAllMapPointsQuery->getStatus();
        $mapPoints = $this->mapPointRepository->getAllMapPoints($page, $status);
        return $mapPoints;
    }
}