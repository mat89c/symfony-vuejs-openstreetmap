<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\DeleteUserCommand;
use App\Messenger\Event\UserDeletedEvent;
use App\Repository\MapPointRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteUserCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    private $mapPointRepository;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus, MapPointRepository $mapPointRepository)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
        $this->mapPointRepository = $mapPointRepository;
    }

    public function __invoke(DeleteUserCommand $deleteUserCommand): void
    {
        $user = $deleteUserCommand->getUser();
        $userMapPoints = $this->mapPointRepository->findBy(['user' => $user]);
        $this->em->remove($user);
        $this->em->flush();

        $this->eventBus->dispatch(new UserDeletedEvent($userMapPoints));
    }
}