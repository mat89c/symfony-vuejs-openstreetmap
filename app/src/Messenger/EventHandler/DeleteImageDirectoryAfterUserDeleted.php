<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\UserDeletedEvent;
use App\Repository\MapPointRepository;
use App\Service\ImageService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteImageDirectoryAfterUserDeleted implements MessageHandlerInterface
{
    private $imageService;

    public function __construct(MapPointRepository $mapPointRepository, ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function __invoke(UserDeletedEvent $userDeletedEvent): void
    {
        $userMapPoints = $userDeletedEvent->getUserMapPoints();
        foreach ($userMapPoints as $mapPoint) {
            $this->imageService->deleteImageDirectoryWithImages($mapPoint);
        }
    }
}