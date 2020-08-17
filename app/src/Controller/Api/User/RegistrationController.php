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
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/register", methods={"POST"})
 */
final class RegistrationController extends AbstractController
{
    private $commandBus;

    private $validatorService;

    private $translator;

    private $tokenGenerator;

    private $passwordEncoder;

    public function __construct(
        MessageBusInterface $commandBus,
        ValidatorService $validatorService,
        TranslatorInterface $translator,
        TokenGeneratorInterface $tokenGenerator,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->commandBus = $commandBus;
        $this->validatorService = $validatorService;
        $this->translator = $translator;
        $this->tokenGenerator = $tokenGenerator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $user = new User();
        $user->setName($params['name']);
        $user->setEmail($params['email']);
        $password = $this->passwordEncoder->encodePassword($user, $params['password']);
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(false);
        $user->setToken($this->tokenGenerator->generateToken());

        $this->validatorService->validate($user);

        $this->commandBus->dispatch(new RegisterUserCommand($user));

        return new ApiResponse(
            $this->translator->trans('registration.email_sended.message'),
            $this->translator->trans('registration.email_sended.title'),
            null,
            [],
            201,
        );
    }
}