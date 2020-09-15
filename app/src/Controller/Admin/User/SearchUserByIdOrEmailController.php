<?php

namespace App\Controller\Admin\User;

use App\MessageBus\QueryBus;
use App\Messenger\Query\SearchUserByIdOrEmailQuery;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user/search", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class SearchUserByIdOrEmailController
{
    private $queryBus;

    private $validatorService;

    public function __construct(QueryBus $queryBus, ValidatorService $validatorService)
    {
        $this->queryBus = $queryBus;
        $this->validatorService = $validatorService;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $value = $request->query->get('value');
        $this->validatorService->validateValueExists($value);
        $users = $this->queryBus->query(new SearchUserByIdOrEmailQuery($value));

        return new ApiResponse(
            '',
            '',
            $users,
            [],
            200
        );
    }
}