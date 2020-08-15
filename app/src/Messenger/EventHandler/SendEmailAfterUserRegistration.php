<?php

namespace App\Messenger\EventHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Event\UserRegisteredEvent;
use App\Service\MailerService;

class SendEmailAfterUserRegistration implements MessageHandlerInterface
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function __invoke(UserRegisteredEvent $userRegisteredEvent): void
    {
        $user = $userRegisteredEvent->getUser();
        $this->mailerService->userRegistered($user);
    }
}

