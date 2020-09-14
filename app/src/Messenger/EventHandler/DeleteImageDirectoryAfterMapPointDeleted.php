<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\MapPointDeletedEvent;
use App\Service\ImageService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteImageDirectoryAfterMapPointDeleted implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(MapPointDeletedEvent $mapPointDeletedEvent): void
    {
        $mapPoint = $mapPointDeletedEvent->getMapPoint();
        /**
         * Prevents the deletion images directory
         * $this->imageService->deleteImageDirectoryWithImages($mapPoint);
         */
    }
}