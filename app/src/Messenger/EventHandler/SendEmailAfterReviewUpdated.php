<?php

namespace App\Messenger\EventHandler;

use App\Messenger\Event\ReviewUpdatedEvent;
use App\Service\MailerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendEmailAfterReviewUpdated implements MessageHandlerInterface
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function __invoke(ReviewUpdatedEvent $reviewUpdatedEvent)
    {
        $review = $reviewUpdatedEvent->getReview();
        $this->mailerService->reviewUpdated($review);
    }
}