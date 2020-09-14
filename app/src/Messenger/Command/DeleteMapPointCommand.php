<?php

namespace App\Messenger\Command;

use App\Entity\MapPoint;

class DeleteMapPointCommand
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