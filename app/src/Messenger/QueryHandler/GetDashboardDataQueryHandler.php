<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetDashboardDataQuery;
use App\Repository\MapPointCategoryRepository;
use App\Repository\MapPointRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Service\BaseUrlService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetDashboardDataQueryHandler implements MessageHandlerInterface
{
    private $userRepository;

    private $mapPointRepository;

    private $reviewRepository;

    private $mapPointCategoryRepository;

    private $baseUrlService;

    public function __construct(
        UserRepository $userRepository,
        MapPointRepository $mapPointRepository,
        ReviewRepository $reviewRepository,
        MapPointCategoryRepository $mapPointCategoryRepository,
        BaseUrlService $baseUrlService)
    {
        $this->userRepository = $userRepository;
        $this->mapPointRepository = $mapPointRepository;
        $this->reviewRepository = $reviewRepository;
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
        $this->baseUrlService = $baseUrlService;
    }

    public function __invoke(GetDashboardDataQuery $getDashboardDataQuery): array
    {
        $inactiveUsers = $this->userRepository->countInactiveUsers();
        $inactiveMapPoints = $this->mapPointRepository->countInactiveMapPoints();
        $inactiveReviews = $this->reviewRepository->countInactiveReviews();
        $inactiveCategories = $this->mapPointCategoryRepository->countInactiveCategories();
        $lastAddedMapPoints = $this->mapPointRepository->getLastAddedMapPoints();

        foreach ($lastAddedMapPoints as $key => $mapPoint) {
            $lastAddedMapPoints[$key]['logo'] = $this->baseUrlService->getImageUrl($mapPoint['uploadDir'], $mapPoint['logo']);
        }

        return [
            'inactiveUsers' => $inactiveUsers,
            'inactiveMapPoints' => $inactiveMapPoints,
            'inactiveReviews' => $inactiveReviews,
            'inactiveCategories' => $inactiveCategories,
            'lastAddedMapPoints' => $lastAddedMapPoints
        ];
    }
}