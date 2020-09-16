<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetMapPointCategoryByIdQuery;
use App\Repository\MapPointCategoryRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetMapPointCategoryByIdQueryHandler implements MessageHandlerInterface
{
    private $mapPointCategoryRepository;

    public function __construct(MapPointCategoryRepository $mapPointCategoryRepository)
    {
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
    }

    public function __invoke(GetMapPointCategoryByIdQuery $getMapPointCategoryByIdQuery): array
    {
        $id = $getMapPointCategoryByIdQuery->getId();
        $mapPointCategory = $this->mapPointCategoryRepository->getMapPointCategoryById($id);

        return $mapPointCategory;
    }
}