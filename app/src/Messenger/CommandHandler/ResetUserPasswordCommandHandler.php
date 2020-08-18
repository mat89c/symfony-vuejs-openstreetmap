<?php

namespace App\Messenger\CommandHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\ResetUserPasswordCommand;
use App\Messenger\Event\UserPasswordChangedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ResetUserPasswordCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(ResetUserPasswordCommand $resetUserPasswordCommand): void
    {
        $user = $resetUserPasswordCommand->getUser();
        $this->em->flush();

        $this->eventBus->dispatch(new UserPasswordChangedEvent($user));
    }
}