<?php

namespace App\Controller\Admin\MapPoint;

use App\Repository\MapPointRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Helper\MapPointImage as Image;
use App\Helper\MapPointImages as Images;
use App\Entity\MapPointImage;
use App\Entity\MapPointCategory;
use App\Messenger\Command\UpdateMapPointCommand;
use App\Repository\MapPointCategoryRepository;
use App\Service\MapPointService;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/point/update", methods={"POST"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class UpdateMapPointController
{
    private $validatorService;

    private $mapPointRepository;

    private $mapPointCategoryRepository;

    private $userRepository;

    private $mapPointService;

    private $translator;

    private $commandBus;

    public function __construct(
        ValidatorService $validatorService,
        MapPointRepository $mapPointRepository,
        UserRepository $userRepository,
        MapPointService $mapPointService,
        TranslatorInterface $translator,
        MessageBusInterface $commandBus,
        MapPointCategoryRepository $mapPointCategoryRepository)
    {
        $this->validatorService = $validatorService;
        $this->mapPointRepository = $mapPointRepository;
        $this->userRepository = $userRepository;
        $this->mapPointService = $mapPointService;
        $this->translator = $translator;
        $this->commandBus = $commandBus;
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $mapPoint = $this->mapPointRepository->findOneBy(['id' => $request->request->get('id')]);
        $this->validatorService->validateMapPoint($mapPoint);

        $user = $this->userRepository->findOneBy(['id' => $request->request->get('user')]);
        $this->validatorService->validateUserExists($user);

        $mapPoint->setUser($user);
        $mapPoint->setTitle($request->request->get('title'));
        $mapPoint->setStreet($request->request->get('street'));
        $mapPoint->setCity($request->request->get('city'));
        $mapPoint->setPostcode($request->request->get('postcode'));
        $mapPoint->setDescription($request->request->get('description'));
        $mapPoint->setColor($request->request->get('color'));
        $mapPoint->setLat($request->request->get('lat'));
        $mapPoint->setLng($request->request->get('lng'));
        $mapPoint->setIsActive($request->request->get('isActive'));

        $mapPointNewLogo = null;
        if ($request->files->get('newLogo'))
            $mapPointNewLogo = new Image($request->files->get('newLogo'));

        $mapPointImages = null;
        if ($request->files->get('newMapPointImages'))
            $mapPointImages = new Images($request->files->get('newMapPointImages'));

        if (isset($mapPointNewLogo))
            $mapPoint->setLogo($mapPointNewLogo->getName());

        if (isset($mapPointImages)) {
            foreach ($mapPointImages->getImages() as $image) {
                $mapPointImage = new MapPointImage();
                $mapPointImage->setName($image->getName());
                $mapPointImage->setMapPoint($mapPoint);

                $mapPoint->addMapPointImage($mapPointImage);
            }
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
                $mapPointCategory->setIsActive(true);
            }

            if ($this->mapPointService->checkCategoryExists($mapPoint->getMapPointCategories(), $mapPointCategory))
                continue;

            $mapPointCategory->addMapPoint($mapPoint);

            $mapPoint->addMapPointCategory($mapPointCategory);
        }

        $this->validatorService->validate($mapPoint);

        if (isset($mapPointNewLogo))
            $this->validatorService->validateImage($mapPointNewLogo);

        if (isset($mapPointImages))
            $this->validatorService->validateImages($mapPointImages);

        $this->commandBus->dispatch(new UpdateMapPointCommand($mapPoint, $mapPointNewLogo, $mapPointImages));

        return new ApiResponse(
            $this->translator->trans('map_point.updated.title'),
            $this->translator->trans('map_point.updated.title'),
            null,
            [],
            200
        );
    }
}