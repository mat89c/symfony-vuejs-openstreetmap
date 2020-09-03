<?php

namespace App\Controller\Api\Review;

use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Review;
use App\Entity\ReviewImage;
use App\Helper\ReviewImages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ValidatorService;
use App\Repository\MapPointRepository;
use App\Messenger\Command\CreateReviewCommand;

/**
 * @Route("/api/review/create", methods={"POST"})
 */
final class CreateReviewController extends AbstractController
{
    private $translator;

    private $commandBus;

    private $validatorService;

    private $mapPointRepository;

    public function __construct(
        TranslatorInterface $translator,
        MessageBusInterface $commandBus,
        ValidatorService $validatorService,
        MapPointRepository $mapPointRepository)
    {
        $this->translator = $translator;
        $this->commandBus = $commandBus;
        $this->validatorService = $validatorService;
        $this->mapPointRepository = $mapPointRepository;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $mapPoint = $this->mapPointRepository->findOneBy(['id' => $request->request->get('mapPointId')]);
        $user = $this->getUser();

        $this->validatorService->validateUserAlreadyPublishedReview($user, $mapPoint);

        $review = new Review();
        $review->setIsActive(false);
        $review->setUser($user);
        $review->setMapPoint($mapPoint);
        $review->setRating($request->request->get('rating'));
        $review->setContent($request->request->get('review'));

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
            $this->translator->trans('review.created.message'),
            $this->translator->trans('review.created.title'),
            null,
            [],
            201
        );
    }
}