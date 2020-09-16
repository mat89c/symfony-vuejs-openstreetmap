<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\UpdateMapPointCategoryCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateMapPointCategoryCommandHandler implements MessageHandlerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(UpdateMapPointCategoryCommand $updateMapPointCategoryCommand)
    {
        $this->em->flush();
    }
}