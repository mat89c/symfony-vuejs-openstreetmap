<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\SendEmailMessageCommand;
use App\Service\MailerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendEmailMessageCommandHandler implements MessageHandlerInterface
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function __invoke(SendEmailMessageCommand $sendEmailMessageCommand): void
    {
        $message = $sendEmailMessageCommand->getMessage();
        $this->mailerService->sendEmailMessage($message);
    }
}