<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\MapPointImageDeletedEvent;
use App\Service\ImageService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteImageFileAfterMapPointImageDeleted implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(MapPointImageDeletedEvent $mapPointImageDeletedEvent)
    {
        $mapPointImage = $mapPointImageDeletedEvent->getMapPointImage();
        $this->imageService->deleteImageFile($mapPointImage->getMapPoint(), $mapPointImage->getName());
    }
}