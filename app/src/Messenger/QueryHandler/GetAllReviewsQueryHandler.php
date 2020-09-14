<?php

namespace  App\Messenger\QueryHandler;

use App\Messenger\Query\GetAllReviewsQuery;
use App\Repository\ReviewRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetAllReviewsQueryHandler implements MessageHandlerInterface
{
    private $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function __invoke(GetAllReviewsQuery $getAllReviewsQuery): array
    {
        $page = $getAllReviewsQuery->getPage();
        $status = $getAllReviewsQuery->getStatus();

        $reviews = $this->reviewRepository->getAllReviews($page, $status);

        return $reviews;
    }
}