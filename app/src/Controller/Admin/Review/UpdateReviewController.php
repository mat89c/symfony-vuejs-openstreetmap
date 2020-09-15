<?php

namespace App\Controller\Admin\Review;

use App\Repository\MapPointRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Helper\ReviewImages;
use App\Entity\ReviewImage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Messenger\Command\UpdateReviewCommand;
use App\Messenger\Event\ReviewMapPointChangedEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/review/{id}/update", methods={"POST"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class UpdateReviewController
{
    private $reviewRepository;

    private $validatorService;

    private $mapPointRepository;

    private $userRepository;

    private $commandBus;

    private $translator;

    private $eventBus;

    public function __construct(
        ReviewRepository $reviewRepository,
        ValidatorService $validatorService,
        MapPointRepository $mapPointRepository,
        UserRepository $userRepository,
        MessageBusInterface $commandBus,
        TranslatorInterface $translator,
        MessageBusInterface $eventBus)
    {
        $this->reviewRepository = $reviewRepository;
        $this->validatorService = $validatorService;
        $this->mapPointRepository = $mapPointRepository;
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->eventBus = $eventBus;
    }

    public function __invoke(int $id, Request $request): ApiResponse
    {
        $mapPointId = $request->request->get('mapPointId');
        $userId = $request->request->get('userId');

        $mapPoint = $this->mapPointRepository->findOneBy(['id' => $mapPointId]);
        $this->validatorService->validateMapPoint($mapPoint);

        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $this->validatorService->validateUserExists($user);

        $review = $this->reviewRepository->findOneBy(['id' => $id]);
        $this->validatorService->validateReviewExists($review);

        $oldImagesDirectory = $review->getMapPoint()->getUploadDir();
        $newImagesDirectory = $mapPoint->getUploadDir();

        $review->setRating($request->request->get('rating'));
        $review->setContent($request->request->get('content'));
        $review->setIsActive($request->request->get('isActive'));
        $review->setUser($user);
        $review->setMapPoint($mapPoint);

        $reviewImages = new ReviewImages($request->files->get('reviewImages'));
        $this->validatorService->validateImages($reviewImages);

        foreach ($reviewImages->getImages() as $image) {
            $reviewImage = new ReviewImage();
            $reviewImage->setName($image->getName());
            $reviewImage->setReview($review);

            $review->addReviewImage($reviewImage);
        }

        $this->validatorService->validate($review);

        $this->commandBus->dispatch(new UpdateReviewCommand($review, $reviewImages));

        if ($oldImagesDirectory !== $newImagesDirectory) {
            $this->eventBus->dispatch(new ReviewMapPointChangedEvent($review->getReviewImages(), $oldImagesDirectory, $newImagesDirectory));
        }

        return new ApiResponse(
            $this->translator->trans('review.updated.title'),
            $this->translator->trans('review.updated.title'),
            null,
            [],
            200
        );
    }
}