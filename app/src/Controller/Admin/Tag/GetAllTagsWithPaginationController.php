<?php

namespace App\Controller\Admin\Tag;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetAllTagsQuery;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/tags", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class GetAllTagsWithPaginationController
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