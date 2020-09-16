<?php

namespace App\Messenger\Command;

use App\Entity\MapPointCategory;

class UpdateMapPointCategoryCommand
{
    private $mapPointCategory;

    public function __construct(MapPointCategory $mapPointCategory)
    {
        $this->mapPointCategory = $mapPointCategory;
    }

    public function getMapPointCategory(): MapPointCategory
    {
        return $this->mapPointCategory;
    }
}