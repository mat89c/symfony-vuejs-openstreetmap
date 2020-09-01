<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\CreateReviewCommand;
use App\Messenger\Event\ReviewCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateReviewCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(CreateReviewCommand $createReviewCommand): void
    {
        $review = $createReviewCommand->getReview();

        $this->em->persist($review);
        $this->em->flush();

        $this->eventBus->dispatch(new ReviewCreatedEvent($review));
    }
}