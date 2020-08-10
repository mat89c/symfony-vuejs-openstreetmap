<?php

namespace App\Messenger\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\RegisterUserCommand;

class RegisterUserCommandHandler implements MessageHandlerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(RegisterUserCommand $registerUserCommand): void
    {
        $this->em->persist($registerUserCommand->getUser());
        $this->em->flush();
        //$this->eventBus->dispatch(new UserRegisteredEvent($registerUserCommand->getName(), $registerUserCommand->getEmail(), $registerUserCommand->getPassword()));
    }
}