<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\DeleteMapPointCategoryCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteMapPointCategoryCommandHandler implements MessageHandlerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(DeleteMapPointCategoryCommand $deleteMapPointCategoryCommand)
    {
        $mapPointCategory = $deleteMapPointCategoryCommand->getMapPointCategory();
        $this->em->remove($mapPointCategory);
        $this->em->flush();
    }
}