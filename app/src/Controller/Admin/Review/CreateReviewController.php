<?php

namespace App\Controller\Admin\Review;

use App\Repository\MapPointRepository;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Review;
use App\Helper\ReviewImages;
use App\Entity\ReviewImage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Messenger\Command\CreateReviewCommand;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/review/create", methods={"POST"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class CreateReviewController
{
    private $userRepository;

    private $mapPointRepository;

    private $validatorService;

    private $commandBus;

    public function __construct(
        UserRepository $userRepository,
        MapPointRepository $mapPointRepository,
        ValidatorService $validatorService,
        MessageBusInterface $commandBus,
        TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->mapPointRepository = $mapPointRepository;
        $this->validatorService = $validatorService;
        $this->commandBus = $commandBus;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $mapPointId = $request->request->get('mapPointId');
        $userId = $request->request->get('userId');

        $mapPoint = $this->mapPointRepository->findOneBy(['id' => $mapPointId]);
        $this->validatorService->validateMapPoint($mapPoint);

        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $this->validatorService->validateUserExists($user);

        $review = new Review();
        $review->setIsActive($request->request->get('isActive'));
        $review->setUser($user);
        $review->setMapPoint($mapPoint);
        $review->setRating($request->request->get('rating'));
        $review->setContent($request->request->get('content'));

        $reviewImages = new ReviewImages($request->files->get('reviewImages'));
        $this->validatorService->validateImages($reviewImages);

        foreach ($reviewImages->getImages() as $image) {
            $reviewImage = new ReviewImage();
            $reviewImage->setName($image->getName());
            $reviewImage->setReview($review);

            $review->addReviewImage($reviewImage);
        }

        $this->validatorService->validate($review);

        $this->commandBus->dispatch(new CreateReviewCommand($review, $reviewImages));

        return new ApiResponse(
            $this->translator->trans('review.created.title'),
            $this->translator->trans('review.created.title'),
            null,
            [],
            201
        );
    }
}