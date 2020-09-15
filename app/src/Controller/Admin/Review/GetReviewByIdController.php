<?php

namespace App\Controller\Admin\Review;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetReviewByIdQuery;
use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/review/{id}", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class GetReviewByIdController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(int $id): ApiResponse
    {
        $review = $this->queryBus->query(new GetReviewByIdQuery($id));

        return new ApiResponse(
            '',
            '',
            $review,
            [],
            200
        );
    }
}