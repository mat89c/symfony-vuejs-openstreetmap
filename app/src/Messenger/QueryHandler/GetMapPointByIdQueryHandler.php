<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetMapPointByIdQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\MapPointRepository;
use App\Service\BaseUrlService;
use App\Exception\ApiException;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\ReviewService;

class GetMapPointByIdQueryHandler implements MessageHandlerInterface
{
    private $mapPointRepository;

    private $baseUrlService;

    private $translator;

    public function __construct(
        MapPointRepository $mapPointRepository,
        BaseUrlService $baseUrlService,
        TranslatorInterface $translator)
    {
        $this->mapPointRepository = $mapPointRepository;
        $this->baseUrlService = $baseUrlService;
        $this->translator = $translator;
    }

    public function __invoke(GetMapPointByIdQuery $getMapPointByIdQuery): array
    {
        $id = $getMapPointByIdQuery->getId();
        $filters = $getMapPointByIdQuery->getFilters();
        $mapPoint = $this->mapPointRepository->getMapPointById($id, $filters);

        if (!isset($mapPoint))
            throw new ApiException($this->translator->trans('map_point.not_found'), 404);

        $mapPoint['logo'] = [
            'src' => $this->baseUrlService->getImageUrl($mapPoint['uploadDir'], $mapPoint['logo']),
            'thumb' => $this->baseUrlService->getThumbnailUrl($mapPoint['uploadDir'], $mapPoint['logo'])
        ];

        foreach ($mapPoint['mapPointImage'] as $key => $image) {
            $mapPoint['mapPointImage'][$key] = [
                'id' => $image['id'],
                'src' => $this->baseUrlService->getImageUrl($mapPoint['uploadDir'], $image['name']),
                'thumb' => $this->baseUrlService->getThumbnailUrl($mapPoint['uploadDir'], $image['name'])
            ];
        }

        return $mapPoint;
    }
}