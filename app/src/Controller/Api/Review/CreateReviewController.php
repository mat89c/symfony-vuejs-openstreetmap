<?php

namespace App\Controller\Api\Review;

use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Review;
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
        $params = json_decode($request->getContent(), true);

        $mapPoint = $this->mapPointRepository->findOneBy(['id' => $params['mapPointId']]);
        $user = $this->getUser();

        $review = new Review();
        $review->setIsActive(true);
        $review->setUser($user);
        $review->setMapPoint($mapPoint);
        $review->setRating($params['rating']);
        $review->setContent($params['review']);

        $this->validatorService->validate($review);
        $this->validatorService->validateUserAlreadyPublishedReview($user, $mapPoint);

        $this->commandBus->dispatch(new CreateReviewCommand($review));

        return new ApiResponse(
            $this->translator->trans('review.created.message'),
            $this->translator->trans('review.created.title'),
            null,
            [],
            201
        );
    }
}