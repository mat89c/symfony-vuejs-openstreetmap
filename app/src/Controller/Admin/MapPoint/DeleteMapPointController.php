<?php

namespace App\Controller\Admin\MapPoint;

use App\Messenger\Command\DeleteMapPointCommand;
use App\Repository\MapPointRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/point/delete", methods={"DELETE"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class DeleteMapPointController
{
    private $commandBus;

    private $translator;

    private $mapPointRepository;

    private $validatorService;

    public function __construct(
        MessageBusInterface $commandBus,
        TranslatorInterface $translator,
        MapPointRepository $mapPointRepository,
        ValidatorService $validatorService)
    {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->mapPointRepository = $mapPointRepository;
        $this->validatorService = $validatorService;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $id = $params['id'];

        $mapPoint = $this->mapPointRepository->findOneBy(['id' => $id]);
        $this->validatorService->validateMapPoint($mapPoint);

        $this->commandBus->dispatch(new DeleteMapPointCommand($mapPoint));

        return new ApiResponse(
            $this->translator->trans('map_point.deleted'),
            $this->translator->trans('map_point.deleted'),
            null,
            [],
            200
        );
    }
}