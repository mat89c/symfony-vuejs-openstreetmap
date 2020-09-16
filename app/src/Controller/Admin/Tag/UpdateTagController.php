<?php

namespace App\Controller\Admin\Tag;

use App\MessageBus\QueryBus;
use App\Messenger\Command\UpdateMapPointCategoryCommand;
use App\Repository\MapPointCategoryRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Messenger\Query\GetMapPointCategoryByIdQuery;

/**
 * @Route("/tag/update", methods={"PATCH"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UpdateTagController
{
    private $mapPointCategoryRepository;

    private $validatorService;

    private $commandBus;

    private $queryBus;

    public function __construct(
        MapPointCategoryRepository $mapPointCategoryRepository,
        ValidatorService $validatorService,
        MessageBusInterface $commandBus,
        QueryBus $queryBus)
    {
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
        $this->validatorService = $validatorService;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $mapPointCategory = $this->mapPointCategoryRepository->findOneBy(['id' => $params['tag']['id']]);
        $this->validatorService->validateMapPointCategory($mapPointCategory);

        $mapPointCategory->setName($params['tag']['name']);
        $mapPointCategory->setIsActive($params['tag']['isActive']);
        $this->validatorService->validate($mapPointCategory);

        $this->commandBus->dispatch(new UpdateMapPointCategoryCommand($mapPointCategory));

        $mapPointCategory = $this->queryBus->query(new GetMapPointCategoryByIdQuery($mapPointCategory->getId()));

        return new ApiResponse(
            '',
            '',
            $mapPointCategory,
            [],
            200
        );
    }
}