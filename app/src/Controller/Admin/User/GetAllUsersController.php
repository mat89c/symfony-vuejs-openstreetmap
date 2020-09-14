<?php

namespace App\Controller\Admin\User;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetAllUsersQuery;
use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/users", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class GetAllUsersController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $page = $request->query->get('page');
        $status = $request->query->get('status');

        $users = $this->queryBus->query(new GetAllUsersQuery($page, $status));

        return new ApiResponse(
            '',
            '',
            $users,
            [],
            200
        );
    }
}