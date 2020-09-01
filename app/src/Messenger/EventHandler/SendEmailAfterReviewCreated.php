<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\ReviewCreatedEvent;
use App\Service\MailerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendEmailAfterReviewCreated implements MessageHandlerInterface
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function __invoke(ReviewCreatedEvent $reviewCreatedEvent): void
    {
        $review = $reviewCreatedEvent->getReview();
        $this->mailerService->reviewCreated($review);
    }
}