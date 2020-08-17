<?php

namespace App\Messenger\CommandHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\SendRegistrationEmailCommand;
use App\Service\MailerService;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class SendRegistrationEmailCommandHandler implements MessageHandlerInterface
{
    private $mailerService;

    private $tokenGenerator;

    private $em;

    public function __construct(MailerService $mailerService, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $em)
    {
        $this->mailerService = $mailerService;
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $em;
    }

    public function __invoke(SendRegistrationEmailCommand $sendRegistrationEmailCommand): void
    {
        $user = $sendRegistrationEmailCommand->getUser();
        $user->setIsActive(false);
        $user->setToken($this->tokenGenerator->generateToken());
        $this->em->flush();

        $this->mailerService->userRegistered($user);
    }
}