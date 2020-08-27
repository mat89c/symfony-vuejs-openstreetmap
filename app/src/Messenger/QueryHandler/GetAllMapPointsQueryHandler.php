<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetAllMapPointsQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\MapPointRepository;
use App\Service\BaseUrlService;

class GetAllMapPointsQueryHandler implements MessageHandlerInterface
{
    private $mapPointRepository;

    private $uploadsDir;

    private $baseUrlService;

    public function __construct(MapPointRepository $mapPointRepository, string $uploadsDir, BaseUrlService $baseUrlService)
    {
        $this->mapPointRepository = $mapPointRepository;
        $this->uploadsDir = $uploadsDir;
        $this->baseUrlService = $baseUrlService;
    }

    public function __invoke(GetAllMapPointsQuery $getAllMapPointsQuery): array
    {
        $mapPoints =  $this->mapPointRepository->getAllMapPoints();

        foreach ($mapPoints as $key => $mapPoint) {
            $mapPoints[$key]['logo'] = $this->baseUrlService->getBaseUrl() . '/' . $this->uploadsDir . '/' . $mapPoint['uploadDir'] . '/' . $mapPoint['logo'] ;
        }

        return $mapPoints;
    }
}