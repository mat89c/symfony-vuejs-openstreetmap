<?php

namespace App\Messenger\CommandHandler;

use App\Messenger\Command\CreateMapPointCategoryCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateMapPointCategoryCommandHandler implements MessageHandlerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(CreateMapPointCategoryCommand $createMapPointCategoryCommand): void
    {
        $mapPointCategory = $createMapPointCategoryCommand->getMapPointCategory();
        $this->em->persist($mapPointCategory);
        $this->em->flush();
    }
}