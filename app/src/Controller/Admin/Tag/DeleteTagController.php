<?php

namespace App\Controller\Admin\Tag;

use App\Messenger\Command\DeleteMapPointCategoryCommand;
use App\Repository\MapPointCategoryRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Route("/tag/delete", methods={"DELETE"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class DeleteTagController
{
    private $commandBus;

    private $mapPointCategoryRepository;

    private $validatorService;

    public function __construct(
        MessageBusInterface $commandBus,
        MapPointCategoryRepository $mapPointCategoryRepository,
        ValidatorService $validatorService)
    {
        $this->commandBus = $commandBus;
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
        $this->validatorService = $validatorService;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $id = $params['id'];
        $mapPointCategory = $this->mapPointCategoryRepository->findOneBy(['id' => $id]);
        $this->validatorService->validateMapPointCategory($mapPointCategory);

        $this->commandBus->dispatch(new DeleteMapPointCategoryCommand($mapPointCategory));

        return new ApiResponse(
            '',
            '',
            null,
            [],
            200
        );
    }
}