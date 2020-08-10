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

/**
 * @Route("/api/register", methods={"POST"})
 */
final class RegistrationController extends AbstractController
{
    private $commandBus;

    private $validatorService;

    public function __construct(MessageBusInterface $commandBus, ValidatorService $validatorService)
    {
        $this->commandBus = $commandBus;
        $this->validatorService = $validatorService;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $user = new User();
        $user->setName($params['name']);
        $user->setEmail($params['email']);
        $user->setPassword($params['password']);

        $this->validatorService->validate($user);

        $this->commandBus->dispatch(new RegisterUserCommand($user));

        return new ApiResponse(
            'Aby dokończyć rejestrację potwierdź swój adres email.',
            'Dokończ rejestrację',
            null,
            [],
            201,
        );
    }
}