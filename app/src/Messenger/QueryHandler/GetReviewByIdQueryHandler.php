<?php

namespace App\Messenger\QueryHandler;

use App\Messenger\Query\GetReviewByIdQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Entity\Review;
use App\Exception\ApiException;
use App\Repository\ReviewRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class GetReviewByIdQueryHandler implements MessageHandlerInterface
{
    private $reviewRepository;

    private $translator;

    public function __construct(ReviewRepository $reviewRepository, TranslatorInterface $translator)
    {
        $this->reviewRepository = $reviewRepository;
        $this->translator = $translator;
    }

    public function __invoke(GetReviewByIdQuery $getReviewByIdQuery): Review
    {
        $review = $this->reviewRepository->findOneBy(['id' => $getReviewByIdQuery->getId()]);

        if (!$review)
            throw new ApiException($this->translator->trans('review.not_found'), 404);

        return $review;
    }
}