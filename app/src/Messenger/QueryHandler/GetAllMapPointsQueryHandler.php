<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetAllMapPointsQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\MapPointRepository;
use App\Service\BaseUrlService;

class GetAllMapPointsQueryHandler implements MessageHandlerInterface
{
    private $mapPointRepository;

    private $baseUrlService;

    public function __construct(MapPointRepository $mapPointRepository, BaseUrlService $baseUrlService)
    {
        $this->mapPointRepository = $mapPointRepository;
        $this->baseUrlService = $baseUrlService;
    }

    public function __invoke(GetAllMapPointsQuery $getAllMapPointsQuery): array
    {
        $mapPoints =  $this->mapPointRepository->getAllMapPoints($getAllMapPointsQuery->getCheckedCategories(), $getAllMapPointsQuery->getMapBounds(), $getAllMapPointsQuery->getPage());

        foreach ($mapPoints as $key => $mapPoint) {
            $mapPoints[$key]['logo'] = $this->baseUrlService->getImageUrl($mapPoint['uploadDir'], $mapPoint['logo']);
        }

        return $mapPoints;
    }
}