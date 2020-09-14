<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetAllTagsQuery;
use App\Repository\MapPointCategoryRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetAllTagsQueryHandler implements MessageHandlerInterface
{
    private $mapPointCategoryRepository;

    public function __construct(MapPointCategoryRepository $mapPointCategoryRepository)
    {
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
    }

    public function __invoke(GetAllTagsQuery $getAllTagsQuery): array
    {
        $page = $getAllTagsQuery->getPage();
        $status = $getAllTagsQuery->getStatus();

        $tags = $this->mapPointCategoryRepository->getAllTags($page, $status);
        return $tags;
    }
}