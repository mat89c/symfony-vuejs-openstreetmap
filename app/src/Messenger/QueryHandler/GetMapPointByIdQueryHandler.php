<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetMapPointByIdQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\MapPointRepository;
use App\Service\BaseUrlService;
use App\Exception\ApiException;
use Symfony\Contracts\Translation\TranslatorInterface;

class GetMapPointByIdQueryHandler implements MessageHandlerInterface
{
    private $mapPointRepository;

    private $uploadsDir;

    private $baseUrlService;

    private $translator;

    public function __construct(
        MapPointRepository $mapPointRepository,
        string $uploadsDir,
        BaseUrlService $baseUrlService,
        TranslatorInterface $translator)
    {
        $this->mapPointRepository = $mapPointRepository;
        $this->uploadsDir = $uploadsDir;
        $this->baseUrlService = $baseUrlService;
        $this->translator = $translator;
    }

    public function __invoke(GetMapPointByIdQuery $getMapPointByIdQuery): array
    {

        $mapPoint = $this->mapPointRepository->getMapPointById($getMapPointByIdQuery->getId());

        if (!isset($mapPoint[0]))
            throw new ApiException($this->translator->trans('map_point'), 404);

        $mapPoint[0]['logo'] = [
            'src' => $this->baseUrlService->getBaseUrl() . '/' .$this->uploadsDir . '/' . $mapPoint[0]['uploadDir'] . '/' . $mapPoint[0]['logo'],
            'thumb' => $this->baseUrlService->getBaseUrl() . '/' .$this->uploadsDir . '/' . $mapPoint[0]['uploadDir'] . '/thumb-' . $mapPoint[0]['logo']
        ];

        foreach ($mapPoint[0]['mapPointImage'] as $key => $image) {
            $mapPoint[0]['mapPointImage'][$key] = [
                'src' => $this->baseUrlService->getBaseUrl() . '/' . $this->uploadsDir . '/' . $mapPoint[0]['uploadDir'] . '/' . $image['name'],
                'thumb' => $this->baseUrlService->getBaseUrl() . '/' . $this->uploadsDir . '/' . $mapPoint[0]['uploadDir'] . '/thumb-' . $image['name']
            ];
        }

        return $mapPoint[0];
    }
}