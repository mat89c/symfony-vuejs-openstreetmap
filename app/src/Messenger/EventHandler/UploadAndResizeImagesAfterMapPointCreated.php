<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\MapPointCreatedEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Service\MapPointFileService;

class UploadAndResizeImagesAfterMapPointCreated implements MessageHandlerInterface
{
    private $mapPointFileService;

    public function __construct(MapPointFileService $mapPointFileService)
    {
        $this->mapPointFileService = $mapPointFileService;
    }

    public function __invoke(MapPointCreatedEvent $mapPointCreatedEvent): void
    {
        $mapPoint = $mapPointCreatedEvent->getMapPoint();
        $mapPointLogo = $mapPointCreatedEvent->getMapPointLogo();
        $mapPointImages = $mapPointCreatedEvent->getMapPointImages();

        $this->mapPointFileService->handleLogo($mapPoint, $mapPointLogo);
        $this->mapPointFileService->handleImages($mapPoint, $mapPointImages);
    }
}