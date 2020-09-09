<?php

namespace App\Messenger\Query;

use App\Entity\MapPoint;

class GetMapPointReviewsQuery
{
    private $mapPoint;

    private $page;

    public function __construct(MapPoint $mapPoint, int $page)
    {
        $this->mapPoint = $mapPoint;
        $this->page = $page;
    }

    public function getMapPoint(): MapPoint
    {
        return $this->mapPoint;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}