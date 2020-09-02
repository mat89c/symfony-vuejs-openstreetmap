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

/**
 * @Route("/api/review/{id}/update", methods={"PATCH"})
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
        $params = json_decode($request->getContent(), true);
        $review = $this->queryBus->query(new GetReviewByIdQuery($id));
        $review->setRating($params['rating']);
        $review->setContent($params['review']);
        $review->setIsActive(false);

        $this->validatorService->validate($review);

        $this->commandBus->dispatch(new UpdateReviewCommand($review));

        return new ApiResponse(
            $this->translator->trans('review.updated.message'),
            $this->translator->trans('review.updated.title'),
            null,
            [],
            201
        );
    }
}