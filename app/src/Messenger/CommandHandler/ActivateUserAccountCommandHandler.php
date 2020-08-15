<?php

namespace App\Messenger\CommandHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\ActivateUserAccountCommand;
use Doctrine\ORM\EntityManagerInterface;

class ActivateuserAccountCommandHandler implements MessageHandlerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(ActivateUserAccountCommand $activateUserAccountCommand): void
    {
        $user = $activateUserAccountCommand->getUser();
        $user->setToken('');
        $user->setIsActive(true);
        $this->em->persist($user);
        $this->em->flush();
    }
}