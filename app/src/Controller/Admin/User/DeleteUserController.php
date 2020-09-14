<?php

namespace App\Controller\Admin\User;

use App\Messenger\Command\DeleteUserCommand;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Route("/user/delete", methods={"DELETE"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class DeleteUserController
{
    private $translator;

    private $userRepository;

    private $validatorService;

    private $commandBus;

    public function __construct(
        TranslatorInterface $translator,
        UserRepository $userRepository,
        ValidatorService $validatorService,
        MessageBusInterface $commandBus)
    {
        $this->translator = $translator;
        $this->userRepository = $userRepository;
        $this->validatorService = $validatorService;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $id = $params['id'];

        $user = $this->userRepository->findOneBy(['id' => $id]);
        $this->validatorService->validateUserExists($user);

        $this->commandBus->dispatch(new DeleteUserCommand($user));

        return new ApiResponse(
            $this->translator->trans('user.deleted'),
            $this->translator->trans('user.deleted'),
            null,
            [],
            200
        );
    }
}