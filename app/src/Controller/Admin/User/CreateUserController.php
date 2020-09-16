<?php

namespace App\Controller\Admin\User;

use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\MessageBus\QueryBus;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Messenger\Command\RegisterUserCommand;
use App\Messenger\Query\GetUserByIdQuery;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Route("/user/create", methods={"POST"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class CreateUserController
{
    private $validatorService;

    private $userPasswordEncoder;

    private $tokenGenerator;

    private $commandBus;

    private $queryBus;

    public function __construct(
        ValidatorService $validatorService,
        UserPasswordEncoderInterface $userPasswordEncoder,
        TokenGeneratorInterface $tokenGenerator,
        MessageBusInterface $commandBus,
        QueryBus $queryBus)
    {
        $this->validatorService = $validatorService;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);

        $user = new User();
        $user->setName($params['user']['name']);
        $user->setIsActive($params['user']['isActive']);
        $user->setRoles([$params['user']['role']]);
        $user->setEmail($params['user']['email']);
        $password = $this->userPasswordEncoder->encodePassword($user, $params['user']['password']);
        $user->setPassword($password);
        $user->setToken($this->tokenGenerator->generateToken());

        $this->validatorService->validate($user);

        $this->commandBus->dispatch(new RegisterUserCommand($user));

        $user = $this->queryBus->query(new GetUserByIdQuery($user->getId()));

        return new ApiResponse(
            '',
            '',
            $user,
            [],
            201
        );
    }
}