<?php

namespace App\Controller\Admin\MapPoint;

use App\MessageBus\QueryBus;
use App\Messenger\Query\SearchMapPointByIdOrNameQuery;
use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/point-search", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
class SearchMapPointByIdOrNameController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $value = $request->query->get('value');
        if (!$value)
            $value = '';

        $mapPoints = $this->queryBus->query(new SearchMapPointByIdOrNameQuery($value));

        return new ApiResponse(
            '',
            '',
            $mapPoints,
            [],
            200
        );
    }
}