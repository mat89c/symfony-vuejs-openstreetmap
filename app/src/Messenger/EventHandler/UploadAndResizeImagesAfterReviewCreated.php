<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\ReviewCreatedEvent;
use App\Service\ImageService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UploadAndResizeImagesAfterReviewCreated implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(ReviewCreatedEvent $reviewCreatedEvent): void
    {
        $mapPoint = $reviewCreatedEvent->getReview()->getMapPoint();
        $reviewImages = $reviewCreatedEvent->getReviewImages();

        $this->imageService->handleImages($mapPoint, $reviewImages);
    }
}