<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\MapPointUpdatedEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Service\ImageService;

class UploadAndResizeImagesAfterMapPointUpdated implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(MapPointUpdatedEvent $mapPointCreatedEvent): void
    {
        $mapPoint = $mapPointCreatedEvent->getMapPoint();
        $mapPointLogo = $mapPointCreatedEvent->getMapPointLogo();
        $mapPointImages = $mapPointCreatedEvent->getMapPointImages();

        if ($mapPointLogo)
            $this->imageService->handleImage($mapPoint, $mapPointLogo);

        if ($mapPointImages)
            $this->imageService->handleImages($mapPoint, $mapPointImages);
    }
}