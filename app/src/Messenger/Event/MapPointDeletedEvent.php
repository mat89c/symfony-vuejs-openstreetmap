<?php

namespace App\Messenger\Event;

use App\Entity\MapPoint;

class MapPointDeletedEvent
{
    private $mapPoint;

    public function __construct(MapPoint $mapPoint)
    {
        $this->mapPoint = $mapPoint;
    }

    public function getMapPoint(): MapPoint
    {
        return $this->mapPoint;
    }
}