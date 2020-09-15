<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\ReviewMapPointChangedEvent;
use App\Service\ImageService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MoveImagesToNewDirectoryAfterReviewMapPointChanged implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(ReviewMapPointChangedEvent $reviewMapPointChangedEvent)
    {
        $reviewImages = $reviewMapPointChangedEvent->getReviewImages();
        $oldImagesDirectory = $reviewMapPointChangedEvent->getOldImagesDirectory();
        $newImagesDirectory = $reviewMapPointChangedEvent->getNewImagesDirectiory();

        foreach ($reviewImages as $image) {
            $this->imageService->moveImageToNewDirectory($image->getName(), $oldImagesDirectory, $newImagesDirectory);
        }
    }
}