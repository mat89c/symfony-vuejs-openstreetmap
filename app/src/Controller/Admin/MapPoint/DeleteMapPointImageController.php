<?php

namespace App\Controller\Admin\MapPoint;

use App\Messenger\Command\DeleteMapPointImageCommand;
use App\Repository\MapPointImageRepository;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\ValidatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/point/image/delete", methods={"DELETE"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
class DeleteMapPointImageController
{
    private $translator;

    private $mapPointImageRepository;

    private $validatorService;

    private $commandBus;

    public function __construct(
        TranslatorInterface $translator,
        MapPointImageRepository $mapPointImageRepository,
        ValidatorService $validatorService,
        MessageBusInterface $commandBus)
    {
        $this->translator = $translator;
        $this->mapPointImageRepository = $mapPointImageRepository;
        $this->validatorService = $validatorService;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $id = $params['id'];

        $mapPointImage = $this->mapPointImageRepository->findOneBy(['id' => $id]);
        $this->validatorService->validateMapPointImageExists($mapPointImage);

        $this->commandBus->dispatch(new DeleteMapPointImageCommand($mapPointImage));

        return new ApiResponse(
            $this->translator->trans('map_point.image.deleted'),
            $this->translator->trans('map_point.image.deleted'),
            null,
            [],
            200
        );
    }
}