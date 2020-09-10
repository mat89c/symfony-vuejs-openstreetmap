<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\ReviewUpdatedEvent;
use App\Repository\ReviewRepository;
use App\Service\ReviewService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateRatingAfterReviewUpdated implements MessageHandlerInterface
{
    private $em;

    private $reviewService;

    private $reviewRepository;

    public function __construct(EntityManagerInterface $em, ReviewService $reviewService, ReviewRepository $reviewRepository)
    {
        $this->em = $em;
        $this->reviewService = $reviewService;
        $this->reviewRepository = $reviewRepository;

    }

    public function __invoke(ReviewUpdatedEvent $reviewUpdatedEvent): void
    {
        $review = $reviewUpdatedEvent->getReview();
        $mapPoint = $review->getMapPoint();
        $mapPoint->setNumberOfReviews($mapPoint->getNumberOfReviews() - 1);
        $activeReviews = $this->reviewRepository->findBy(['mapPoint' => $mapPoint, 'isActive' => true]);
        $rating = $this->reviewService->getAvgRating($activeReviews);
        $mapPoint->setRating($rating);

        $this->em->persist($mapPoint);
        $this->em->flush();
    }
}