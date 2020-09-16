<?php

namespace App\Controller\Admin\Tag;

use App\Response\ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Repository\MapPointCategoryRepository;

/**
 * @Route("/tags/all", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class GetAllTagsController
{
    private $mapPointCategoryRepository;

    public function __construct(MapPointCategoryRepository $mapPointCategoryRepository)
    {
        $this->mapPointCategoryRepository = $mapPointCategoryRepository;
    }

    public function __invoke(): ApiResponse
    {
        $categories = $this->mapPointCategoryRepository->getAllCategories();

        return new ApiResponse(
            '',
            '',
            $categories,
            [],
            200
        );
    }
}