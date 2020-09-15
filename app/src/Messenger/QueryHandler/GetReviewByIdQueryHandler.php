<?php

namespace App\Messenger\QueryHandler;

use App\Exception\ApiException;
use App\Messenger\Query\GetReviewByIdQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\ReviewRepository;
use App\Service\BaseUrlService;
use Symfony\Contracts\Translation\TranslatorInterface;

class GetReviewByIdQueryHandler implements MessageHandlerInterface
{
    private $reviewRepository;

    private $baseUrlService;

    private $translator;

    public function __construct(ReviewRepository $reviewRepository, BaseUrlService $baseUrlService, TranslatorInterface $translator)
    {
        $this->reviewRepository = $reviewRepository;
        $this->baseUrlService = $baseUrlService;
        $this->translator = $translator;
    }

    public function __invoke(GetReviewByIdQuery $getReviewByIdQuery): array
    {
        $id = $getReviewByIdQuery->getId();
        $review = $this->reviewRepository->getReviewById($id);

        if (!$review)
            throw new ApiException($this->translator->trans('review.not_found'), 404);

        foreach ($review['reviewImages'] as $key => $image) {
            $review['reviewImages'][$key] = [
                'id' => $image['id'],
                'src' => $this->baseUrlService->getImageUrl($review['mapPoint']['uploadDir'], $image['name']),
                'thumb' => $this->baseUrlService->getThumbnailUrl($review['mapPoint']['uploadDir'], $image['name'])
            ];
        }

        return $review;
    }
}