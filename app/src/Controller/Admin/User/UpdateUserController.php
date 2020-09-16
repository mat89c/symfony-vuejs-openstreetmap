<?php

namespace App\Controller\Admin\User;

use App\MessageBus\QueryBus;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Messenger\Command\RegisterUserCommand;
use App\Messenger\Query\GetUserByIdQuery;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Route("/user/update", methods={"PATCH"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class UpdateUserController
{
    private $userRepository;

    private $validatorService;

    private $userPasswordEncoder;

    private $commandBus;

    private $queryBus;

    public function __construct(
        UserRepository $userRepository,
        ValidatorService $validatorService,
        UserPasswordEncoderInterface $userPasswordEncoder,
        MessageBusInterface $commandBus,
        QueryBus $queryBus)
    {
        $this->userRepository = $userRepository;
        $this->validatorService = $validatorService;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['id' => $params['user']['id']]);
        $this->validatorService->validateUserExists($user);
        $this->validatorService->preventChangesOnAdminAccount($user);

        $user->setName($params['user']['name']);
        $user->setIsActive($params['user']['isActive']);
        $user->setRoles([$params['user']['role']]);
        $user->setEmail($params['user']['email']);

        if (isset($params['user']['password'])) {
            $password = $this->userPasswordEncoder->encodePassword($user, $params['user']['password']);
            $user->setPassword($password);
        }

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