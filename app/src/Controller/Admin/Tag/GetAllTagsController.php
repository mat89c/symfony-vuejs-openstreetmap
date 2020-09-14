<?php

namespace App\Controller\Admin\Tag;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetAllTagsQuery;
use App\Messenger\Query\GetAllUsersQuery;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tags", methods={"GET"})
 */
final class GetAllTagsController
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

        $tags = $this->queryBus->query(new GetAllTagsQuery($page, $status));

        return new ApiResponse(
            '',
            '',
            $tags,
            [],
            200
        );
    }
}