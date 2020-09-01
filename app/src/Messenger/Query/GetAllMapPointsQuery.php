<?php

namespace App\Messenger\Query;

class GetAllMapPointsQuery
{
    private $checkedCategories;

    public function __construct(?array $checkedCategories)
    {
        $this->checkedCategories = $checkedCategories;
    }

    public function getCheckedCategories(): ?array
    {
        return $this->checkedCategories;
    }
}