<?php

namespace App\Controller\Admin\Dashboard;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetDashboardDataQuery;
use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/get-dashboard-data", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class GetDashboardDataController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(): ApiResponse
    {
        $dashboardData = $this->queryBus->query(new GetDashboardDataQuery());

        return new ApiResponse(
            '',
            '',
            $dashboardData,
            [],
            201
        );
    }
}