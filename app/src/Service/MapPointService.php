<?php

namespace App\Service;

use App\Entity\MapPointCategory;
use Doctrine\Common\Collections\Collection;

class MapPointService
{
    public function checkCategoryExists(?Collection $categories = null, MapPointCategory $category)
    {
        if ($categories) {
            foreach ($categories as $item) {
                if ($item->getId() === $category->getId())
                    return true;
            }
        }

        return false;
    }
}