<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\DeleteMapPointImageCommand;
use App\Messenger\Event\MapPointImageDeletedEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteMapPointImageCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(DeleteMapPointImageCommand $deleteMapPointImageCommand): void
    {
        $mapPointImage = $deleteMapPointImageCommand->getMapPointImage();
        $this->em->remove($mapPointImage);
        $this->em->flush();

        $this->eventBus->dispatch(new MapPointImageDeletedEvent($mapPointImage));
    }
}