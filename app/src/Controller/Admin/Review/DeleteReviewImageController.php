<?php

namespace App\Controller\Admin\Review;

use App\Repository\ReviewImageRepository;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Messenger\Command\DeleteReviewImageCommand;
use App\Service\ValidatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/review/image/delete", methods={"DELETE"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
class DeleteReviewImageController
{
    private $translator;

    private $reviewImageRepository;

    private $validatorService;

    private $commandBus;

    public function __construct(
        TranslatorInterface $translator,
        ReviewImageRepository $reviewImageRepository,
        ValidatorService $validatorService,
        MessageBusInterface $commandBus)
    {
        $this->translator = $translator;
        $this->reviewImageRepository = $reviewImageRepository;
        $this->validatorService = $validatorService;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $id = $params['id'];

        $reviewImage = $this->reviewImageRepository->findOneBy(['id' => $id]);
        $this->validatorService->validateReviewImageExists($reviewImage);

        $this->commandBus->dispatch(new DeleteReviewImageCommand($reviewImage));

        return new ApiResponse(
            $this->translator->trans('review.image.deleted'),
            $this->translator->trans('review.image.deleted'),
            null,
            [],
            200
        );
    }
}