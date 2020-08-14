<?php

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Messenger\Command\RegisterUserCommand;
use App\Entity\User;
use App\Service\ValidatorService;
use App\Response\ApiResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/register", methods={"POST"})
 */
final class RegistrationController extends AbstractController
{
    private $commandBus;

    private $validatorService;

    private $translator;

    public function __construct(MessageBusInterface $commandBus, ValidatorService $validatorService, TranslatorInterface $translator)
    {
        $this->commandBus = $commandBus;
        $this->validatorService = $validatorService;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $user = new User();
        $user->setName($params['name']);
        $user->setEmail($params['email']);
        $user->setPassword($params['password']);
        $user->setRoles(['ROLE_USER']);

        $this->validatorService->validate($user);

        $this->commandBus->dispatch(new RegisterUserCommand($user));

        return new ApiResponse(
            $this->translator->trans('confirm_email.message'),
            $this->translator->trans('confirm_email.title'),
            null,
            [],
            201,
        );
    }
}