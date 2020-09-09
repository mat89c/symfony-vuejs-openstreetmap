<?php

namespace App\Messenger\Query;

class GetAllMapPointsQuery
{
    private $checkedCategories;

    private $mapBounds;

    private $page;

    public function __construct(?array $checkedCategories, ?array $mapBounds, int $page)
    {
        $this->checkedCategories = $checkedCategories;
        $this->mapBounds = $mapBounds;
        $this->page = $page;
    }

    public function getCheckedCategories(): ?array
    {
        return $this->checkedCategories;
    }

    public function getMapBounds(): ?array
    {
        return $this->mapBounds;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}