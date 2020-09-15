<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\SearchMapPointByIdOrNameQuery;
use App\Repository\MapPointRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SearchMapPointByIdOrNameQueryHandler implements MessageHandlerInterface
{
    private $mapPointRepository;

    public function __construct(MapPointRepository $mapPointRepository)
    {
        $this->mapPointRepository = $mapPointRepository;
    }

    public function __invoke(SearchMapPointByIdOrNameQuery $searchMapPointByIdOrNameQuery): array
    {
        $value = $searchMapPointByIdOrNameQuery->getValue();
        $mapPoints = $this->mapPointRepository->searchMapPointByIdOrName($value);

        return $mapPoints;
    }
}