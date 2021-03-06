<?php

namespace App\Messenger\Command;

use App\Entity\MapPointCategory;

class DeleteMapPointCategoryCommand
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