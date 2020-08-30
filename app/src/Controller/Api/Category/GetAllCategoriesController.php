<?php

namespace App\Controller\Api\Category;

use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use App\Repository\MapPointCategoryRepository;

/**
 * @Route("/api/categories", methods={"GET"})
 */
final class GetAllCategoriesController
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