<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\DeleteReviewCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteReviewCommandHandler implements MessageHandlerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(DeleteReviewCommand $deleteReviewCommand): void
    {
        $review = $deleteReviewCommand->getReview();
        $this->em->remove($review);
        $this->em->flush();
    }
}