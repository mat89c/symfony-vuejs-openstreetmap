<?php

namespace App\Messenger\CommandHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\SendResetPasswordEmailCommand;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SendResetPasswordEmailCommandHandler implements MessageHandlerInterface
{
    private $mailerService;

    private $tokenGenerator;

    private $em;

    public function __construct(
        MailerService $mailserService,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $em)
    {
        $this->mailerService = $mailserService;
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $em;
    }

    public function __invoke(SendResetPasswordEmailCommand $sendResetPasswordEmailCommand): void
    {
        $user = $sendResetPasswordEmailCommand->getUser();
        $user->setToken($this->tokenGenerator->generateToken());
        $this->em->flush();

        $this->mailerService->resetPassword($user);
    }
}