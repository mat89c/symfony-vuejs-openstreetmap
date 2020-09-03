<?php

namespace App\Controller\Api\Review;

use App\Messenger\Command\DeleteReviewImageCommand;
use App\Repository\ReviewImageRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/review/image/delete", methods={"DELETE"})
 */
final class DeleteReviewImageController extends AbstractController
{
    private $translator;

    private $validatorService;

    private $reviewImageRepository;

    private $commandBus;

    public function __construct(
        TranslatorInterface $translator,
        ValidatorService $validatorService,
        ReviewImageRepository $reviewImageRepository,
        MessageBusInterface $commandBus)
    {
        $this->translator = $translator;
        $this->validatorService = $validatorService;
        $this->reviewImageRepository = $reviewImageRepository;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $imageId = $params['imageId'];
        $user = $this->getUser();

        $reviewImage = $this->reviewImageRepository->findOneBy(['id' => $imageId]);
        $this->validatorService->validateUserCanDeleteReviewImage($reviewImage, $user);

        $this->commandBus->dispatch(new DeleteReviewImageCommand($reviewImage));

        return new ApiResponse(
            $this->translator->trans('review.image.deleted'),
            $this->translator->trans('review.image.deleted'),
            null,
            [],
            201
        );
    }
}