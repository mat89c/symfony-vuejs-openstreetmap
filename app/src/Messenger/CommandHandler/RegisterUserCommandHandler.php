<?php

namespace App\Messenger\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\RegisterUserCommand;
use App\Messenger\Event\UserRegisteredEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterUserCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(RegisterUserCommand $registerUserCommand): void
    {
        $this->em->persist($registerUserCommand->getUser());
        $this->em->flush();

        $this->eventBus->dispatch(new UserRegisteredEvent($registerUserCommand->getUser()));
    }
}