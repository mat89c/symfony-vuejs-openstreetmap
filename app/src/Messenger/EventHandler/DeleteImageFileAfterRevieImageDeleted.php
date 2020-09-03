<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\ReviewImageDeletedEvent;
use App\Service\ImageService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteImageFileAfterRevieImageDeleted implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(ReviewImageDeletedEvent $reviewImageDeletedEvent)
    {
        $reviewImage = $reviewImageDeletedEvent->getReviewImage();
        $this->imageService->deleteImageFile($reviewImage->getReview()->getMapPoint(), $reviewImage->getName());
    }
}