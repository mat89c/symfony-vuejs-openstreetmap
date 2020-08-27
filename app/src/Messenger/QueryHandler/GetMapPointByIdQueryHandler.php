<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetMapPointByIdQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\MapPointRepository;
use App\Service\BaseUrlService;

class GetMapPointByIdQueryHandler implements MessageHandlerInterface
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

    public function __invoke(GetMapPointByIdQuery $getMapPointByIdQuery): array
    {

        $mapPoint = $this->mapPointRepository->getMapPointById($getMapPointByIdQuery->getId());

        $mapPoint[0]['logo'] = $this->baseUrlService->getBaseUrl() . '/' .$this->uploadsDir . '/' . $mapPoint[0]['uploadDir'] . '/' . $mapPoint[0]['logo'];

        foreach ($mapPoint[0]['mapPointImage'] as $key => $image) {
            $mapPoint[0]['mapPointImage'][$key] = $this->baseUrlService->getBaseUrl() . '/' . $this->uploadsDir . '/' . $mapPoint[0]['uploadDir'] . '/' . $image['name'];
        }

        return $mapPoint[0];
    }
}