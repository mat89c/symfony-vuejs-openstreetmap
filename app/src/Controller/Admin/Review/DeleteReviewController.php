<?php

namespace App\Controller\Admin\Review;

use App\Messenger\Command\DeleteReviewCommand;
use App\Repository\ReviewRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/review/delete", methods={"DELETE"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class DeleteReviewController
{
    private $translator;

    private $reviewRepository;

    private $validatorService;

    private $commandBus;

    public function __construct(
        TranslatorInterface $translator,
        ReviewRepository $reviewRepository,
        ValidatorService $validatorService,
        MessageBusInterface $commandBus)
    {
        $this->translator = $translator;
        $this->reviewRepository = $reviewRepository;
        $this->validatorService = $validatorService;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $id = $params['id'];
        $review = $this->reviewRepository->findOneBy(['id' => $id]);
        $this->validatorService->validateReviewExists($review);

        $this->commandBus->dispatch(new DeleteReviewCommand($review));

        return new ApiResponse(
            $this->translator->trans('review.deleted'),
            $this->translator->trans('review.deleted'),
            null,
            [],
            200
        );
    }
}