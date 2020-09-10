<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\UpdateReviewCommand;
use App\Messenger\Event\ReviewUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateReviewCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(UpdateReviewCommand $updateReviewCommand)
    {
        $review = $updateReviewCommand->getReview();
        $reviewImages = $updateReviewCommand->getReviewImages();

        $this->em->persist($review);
        $this->em->flush();

        $this->eventBus->dispatch(new ReviewUpdatedEvent($review, $reviewImages));
    }
}