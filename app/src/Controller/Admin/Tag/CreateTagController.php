<?php

namespace App\Controller\Admin\Tag;

use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\MapPointCategory;
use App\MessageBus\QueryBus;
use App\Messenger\Command\CreateMapPointCategoryCommand;
use App\Messenger\Query\GetMapPointCategoryByIdQuery;
use App\Service\ValidatorService;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Route("/tag/create", methods={"POST"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class CreateTagController
{
    private $validatorService;

    private $commandBus;

    private $queryBus;

    public function __construct(
        ValidatorService $validatorService,
        MessageBusInterface $commandBus,
        QueryBus $queryBus)
    {
        $this->validatorService = $validatorService;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $mapPointCategory = new MapPointCategory();
        $mapPointCategory->setName($params['tag']['name']);
        $mapPointCategory->setIsActive($params['tag']['isActive']);

        $this->validatorService->validate($mapPointCategory);

        $this->commandBus->dispatch(new CreateMapPointCategoryCommand($mapPointCategory));

        $mapPointCategory = $this->queryBus->query(new GetMapPointCategoryByIdQuery($mapPointCategory->getId()));

        return new ApiResponse(
            '',
            '',
            $mapPointCategory,
            [],
            201
        );
    }
}