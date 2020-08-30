<?php

namespace App\Controller\Api\MapPoint;

use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Entity\MapPoint;
use App\Entity\MapPointImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Helper\MapPointFile;
use App\Helper\MapPointFiles;
use App\Service\ValidatorService;
use App\Messenger\Command\CreateMapPointCommand;
use App\Entity\MapPointCategory;
use App\Exception\ApiException;
use App\Repository\MapPointCategoryRepository;

/**
 * @Route("/api/point/create", methods={"POST"})
 */
final class CreateMapPointController extends AbstractController
{
    private $commandBus;

    private $translator;

    private $validatorService;

    private $mapPointCategoryRepository;

    public function __construct(
        MessageBusInterface $commandBus,
        TranslatorInterface $translator,
        ValidatorService $validatorService,
        MapPointCategoryRepository $mapPointCategoryRepository)
    {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->validatorService = $validatorService;
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $mapPoint = new MapPoint();
        $mapPoint->setUser($this->getUser());
        $mapPoint->setTitle($request->request->get('title'));
        $mapPoint->setStreet($request->request->get('street'));
        $mapPoint->setCity($request->request->get('city'));
        $mapPoint->setPostcode($request->request->get('postcode'));
        $mapPoint->setDescription($request->request->get('description'));
        $mapPoint->setColor($request->request->get('color'));
        $mapPoint->setLat($request->request->get('lat'));
        $mapPoint->setLng($request->request->get('lng'));
        $mapPoint->setUploadDir(uniqid());
        $mapPoint->setIsActive(false);

        $mapPointLogo = new MapPointFile($request->files->get('logo'));
        $mapPointImages = new MapPointFiles($request->files->get('images'));

        $mapPoint->setLogo($mapPointLogo->getName());

        foreach ($mapPointImages->getFiles() as $item) {
            $mapPointImage = new MapPointImage();
            $mapPointImage->setName($item->getName());
            $mapPointImage->setMapPoint($mapPoint);

            $mapPoint->addMapPointImage($mapPointImage);
        }

        $categories = $request->request->get('categories');

        if (!$categories)
            throw new ApiException($this->translator->trans('category.not_blank'), 400);

        if (count($categories) > 5)
            throw new ApiException($this->translator->trans('category.max_qty'), 400);


        foreach ($categories as $category) {
            if (is_array($category)) {
                $mapPointCategory = $this->mapPointCategoryRepository->findOneBy(['id' => $category['id']]);
                if (!$mapPointCategory)
                    throw new ApiException($this->translator->trans('category.not_found'), 400);
            } else {
                $mapPointCategory = new MapPointCategory();
                $mapPointCategory->setName($category);
                $mapPointCategory->setIsActive(false);
            }
            $mapPointCategory->addMapPoint($mapPoint);

            $mapPoint->addMapPointCategory($mapPointCategory);
        }

        $this->validatorService->validate($mapPoint);
        $this->validatorService->validateMapPointFile($mapPointLogo);
        $this->validatorService->validateMapPointFiles($mapPointImages);

        $this->commandBus->dispatch(new CreateMapPointCommand($mapPoint, $mapPointLogo, $mapPointImages));

        return new ApiResponse(
            $this->translator->trans('map_point.created.message'),
            $this->translator->trans('map_point.created.title'),
            null,
            [],
            201
        );
    }
}