<?php

namespace App\Controller\Api\MapPoint;

use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Entity\MapPoint;
use App\Entity\MapPointImage;
use App\Helper\MapPointImage as Image;
use App\Helper\MapPointImages as Images;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\ValidatorService;
use App\Messenger\Command\CreateMapPointCommand;
use App\Entity\MapPointCategory;
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

        $mapPointLogo = new Image($request->files->get('logo'));
        $mapPointImages = new Images($request->files->get('images'));

        $mapPoint->setLogo($mapPointLogo->getName());

        foreach ($mapPointImages->getImages() as $image) {
            $mapPointImage = new MapPointImage();
            $mapPointImage->setName($image->getName());
            $mapPointImage->setMapPoint($mapPoint);

            $mapPoint->addMapPointImage($mapPointImage);
        }

        $categories = $request->request->get('categories');
        $this->validatorService->validateMapPointCategories($categories);

        foreach ($categories as $category) {
            if (is_array($category)) {
                $mapPointCategory = $this->mapPointCategoryRepository->findOneBy(['id' => $category['id']]);
                $this->validatorService->validateMapPointCategory($mapPointCategory);
            } else {
                $mapPointCategory = new MapPointCategory();
                $mapPointCategory->setName($category);
                $mapPointCategory->setIsActive(false);
            }
            $mapPointCategory->addMapPoint($mapPoint);

            $mapPoint->addMapPointCategory($mapPointCategory);
        }

        $this->validatorService->validate($mapPoint);
        $this->validatorService->validateImage($mapPointLogo);
        $this->validatorService->validateImages($mapPointImages);

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