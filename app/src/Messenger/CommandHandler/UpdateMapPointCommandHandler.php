<?php

namespace App\Messenger\CommandHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\UpdateMapPointCommand;
use App\Messenger\Event\MapPointUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateMapPointCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(UpdateMapPointCommand $updateMapPointCommand): void
    {
        $mapPoint = $updateMapPointCommand->getMapPoint();

        $this->em->persist($mapPoint);
        $this->em->flush();

        $this->eventBus->dispatch(new MapPointUpdatedEvent(
            $mapPoint,
            $updateMapPointCommand->getMapPointLogo(),
            $updateMapPointCommand->getMapPointImages()
        ));
    }
}