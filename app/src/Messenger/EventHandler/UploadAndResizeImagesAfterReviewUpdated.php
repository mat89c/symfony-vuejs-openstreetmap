<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\ReviewUpdatedEvent;
use App\Service\ImageService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UploadAndResizeImagesAfterReviewUpdated implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(ReviewUpdatedEvent $reviewUpdatedEvent): void
    {
        $mapPoint = $reviewUpdatedEvent->getReview()->getMapPoint();
        $reviewImages = $reviewUpdatedEvent->getReviewImages();

        $this->imageService->handleImages($mapPoint, $reviewImages);
    }
}