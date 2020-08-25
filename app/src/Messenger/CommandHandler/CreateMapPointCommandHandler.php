<?php

namespace App\Messenger\CommandHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\CreateMapPointCommand;
use App\Messenger\Event\MapPointCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateMapPointCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(CreateMapPointCommand $createMapPointCommand): void
    {
        $mapPoint = $createMapPointCommand->getMapPoint();

        $this->em->persist($mapPoint);
        $this->em->flush();

        $this->eventBus->dispatch(new MapPointCreatedEvent(
            $mapPoint,
            $createMapPointCommand->getMapPointLogo(),
            $createMapPointCommand->getMapPointImages()
        ));
    }
}