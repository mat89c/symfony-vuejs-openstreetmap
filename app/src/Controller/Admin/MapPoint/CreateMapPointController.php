<?php

namespace App\Controller\Admin\MapPoint;

use App\Entity\MapPoint;
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
use App\Messenger\Command\CreateMapPointCommand;
use App\Repository\MapPointCategoryRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/point/create", methods={"POST"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class CreateMapPointController
{
    private $validatorService;

    private $mapPointCategoryRepository;

    private $userRepository;

    private $translator;

    private $commandBus;

    public function __construct(
        ValidatorService $validatorService,
        UserRepository $userRepository,
        TranslatorInterface $translator,
        MessageBusInterface $commandBus,
        MapPointCategoryRepository $mapPointCategoryRepository)
    {
        $this->validatorService = $validatorService;
        $this->userRepository = $userRepository;
        $this->translator = $translator;
        $this->commandBus = $commandBus;
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $request->request->get('user')]);
        $this->validatorService->validateUserExists($user);

        $mapPoint = new MapPoint();
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
        $mapPoint->setUploadDir(uniqid());
        $mapPoint->setRating(0);
        $mapPoint->setNumberOfReviews(0);

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

            $mapPointCategory->addMapPoint($mapPoint);

            $mapPoint->addMapPointCategory($mapPointCategory);
        }

        $this->validatorService->validate($mapPoint);

        if (isset($mapPointNewLogo))
            $this->validatorService->validateImage($mapPointNewLogo);

        if (isset($mapPointImages))
            $this->validatorService->validateImages($mapPointImages);

        $this->commandBus->dispatch(new CreateMapPointCommand($mapPoint, $mapPointNewLogo, $mapPointImages));

        return new ApiResponse(
            $this->translator->trans('map_point.created.title'),
            $this->translator->trans('map_point.created.title'),
            null,
            [],
            201
        );
    }
}