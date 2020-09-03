<?php

namespace App\Messenger\CommandHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Messenger\Command\DeleteReviewImageCommand;
use App\Messenger\Event\ReviewImageDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteReviewImageCommandHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $eventBus)
    {
        $this->em = $em;
        $this->eventBus = $eventBus;
    }

    public function __invoke(DeleteReviewImageCommand $deleteReviewImageCommand): void
    {
        $reviewImage = $deleteReviewImageCommand->getReviewImage();

        $this->em->remove($reviewImage);
        $this->em->flush();

        $this->eventBus->dispatch(new ReviewImageDeletedEvent($reviewImage));
    }
}