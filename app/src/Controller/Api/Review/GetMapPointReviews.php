<?php

namespace App\Controller\Api\Review;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetMapPointByIdQuery;
use App\Messenger\Query\GetMapPointReviewsQuery;
use App\Repository\MapPointRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/reviews/{mapPointId}/{page}", methods={"GET"})
 */
final class GetMapPointReviews
{
    private $queryBus;

    private $validatorService;

    private $mapPointRepository;

    public function __construct(QueryBus $queryBus, ValidatorService $validatorService, MapPointRepository $mapPointRepository)
    {
        $this->queryBus = $queryBus;
        $this->validatorService = $validatorService;
        $this->mapPointRepository = $mapPointRepository;
    }

    public function __invoke(int $mapPointId, int $page): ApiResponse
    {
        $mapPoint = $this->mapPointRepository->findOneBy(['id' => $mapPointId]);
        $this->validatorService->validateMapPoint(($mapPoint));

        $reviews = $this->queryBus->query(new GetMapPointReviewsQuery($mapPoint, $page));

        return new ApiResponse(
            '',
            '',
            $reviews,
            [],
            200
        );
    }
}