<?php

namespace App\Messenger\QueryHandler;

use App\Entity\Review;
use App\Messenger\Query\GetMapPointReviewsQuery;
use App\Repository\ReviewRepository;
use App\Service\BaseUrlService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetMapPointReviewsQueryHandler implements MessageHandlerInterface
{
    private $reviewRepository;

    private $baseUrlService;

    public function __construct(ReviewRepository $reviewRepository, BaseUrlService $baseUrlService)
    {
        $this->reviewRepository = $reviewRepository;
        $this->baseUrlService = $baseUrlService;
    }

    public function __invoke(GetMapPointReviewsQuery $getMapPointReviewsQuery): array
    {
        $mapPoint = $getMapPointReviewsQuery->getMapPoint();
        $page = $getMapPointReviewsQuery->getPage();

        $reviews = $this->reviewRepository->getMapPointReviews($mapPoint->getId(), $page);

        foreach ($reviews as $i => $review) {
            foreach($review['reviewImages'] as $j => $image) {
                $reviews[$i]['reviewImages'][$j] = [
                    'id' => $image['id'],
                    'src' => $this->baseUrlService->getImageUrl($mapPoint->getUploadDir(), $image['name']),
                    'thumb' => $this->baseUrlService->getThumbnailUrl($mapPoint->getUploadDir(), $image['name'])
                ];
            }
        }

        return $reviews;
    }
}