<?php

namespace App\Messenger\EventHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Event\UserPasswordChangedEvent;
use App\Service\MailerService;

class SendEmailAfterUserPasswordChanged implements MessageHandlerInterface
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function __invoke(UserPasswordChangedEvent $userPasswordChangedEvent): void
    {
        $user =  $userPasswordChangedEvent->getUser();
        $this->mailerService->userPasswordChanged($user);
    }
}