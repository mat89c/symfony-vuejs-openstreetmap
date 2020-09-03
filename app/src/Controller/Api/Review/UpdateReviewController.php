<?php

namespace App\Controller\Api\Review;

use App\MessageBus\QueryBus;
use App\Messenger\Command\UpdateReviewCommand;
use App\Messenger\Query\GetReviewByIdQuery;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Helper\ReviewImages;
use App\Entity\ReviewImage;

/**
 * @Route("/api/review/{id}/update", methods={"POST"})
 */
final class UpdateReviewController
{
    private $queryBus;

    private $validatorService;

    private $translator;

    private $commandBus;

    public function __construct(
        QueryBus $queryBus,
        ValidatorService $validatorService,
        TranslatorInterface $translator,
        MessageBusInterface $commandBus)
    {
        $this->queryBus = $queryBus;
        $this->validatorService = $validatorService;
        $this->translator = $translator;
        $this->commandBus = $commandBus;
    }

    public function __invoke(int $id, Request $request): ApiResponse
    {
        $review = $this->queryBus->query(new GetReviewByIdQuery($id));
        $review->setRating($request->request->get('rating'));
        $review->setContent($request->request->get('review'));
        $review->setIsActive(false);

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

        return new ApiResponse(
            $this->translator->trans('review.updated.message'),
            $this->translator->trans('review.updated.title'),
            null,
            [],
            201
        );
    }
}