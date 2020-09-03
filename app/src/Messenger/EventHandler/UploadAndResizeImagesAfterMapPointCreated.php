<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\MapPointCreatedEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Service\ImageService;

class UploadAndResizeImagesAfterMapPointCreated implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(MapPointCreatedEvent $mapPointCreatedEvent): void
    {
        $mapPoint = $mapPointCreatedEvent->getMapPoint();
        $mapPointLogo = $mapPointCreatedEvent->getMapPointLogo();
        $mapPointImages = $mapPointCreatedEvent->getMapPointImages();

        $this->imageService->handleImage($mapPoint, $mapPointLogo);
        $this->imageService->handleImages($mapPoint, $mapPointImages);
    }
}