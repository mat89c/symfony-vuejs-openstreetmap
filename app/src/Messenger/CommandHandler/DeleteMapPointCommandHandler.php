<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\DeleteMapPointCommand;
use App\Messenger\Event\MapPointDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteMapPointCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(DeleteMapPointCommand $deleteMapPointCommand): void
    {
        $mapPoint = $deleteMapPointCommand->getMapPoint();
        $this->em->remove($mapPoint);
        $this->em->flush();

        $this->eventBus->dispatch(new MapPointDeletedEvent($mapPoint));
    }
}