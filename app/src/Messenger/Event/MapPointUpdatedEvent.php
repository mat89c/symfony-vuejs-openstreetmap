<?php

namespace App\Messenger\Event;

use App\Entity\MapPoint;
use App\Helper\MapPointImage;
use App\Helper\MapPointImages;

class MapPointUpdatedEvent
{
    private $mapPoint;

    private $mapPointLogo;

    private $mapPointImages;

    public function __construct(MapPoint $mapPoint, MapPointImage $mapPointLogo = null, MapPointImages $mapPointImages = null)
    {
        $this->mapPoint = $mapPoint;
        $this->mapPointLogo = $mapPointLogo;
        $this->mapPointImages = $mapPointImages;
    }

    public function getMapPoint(): MapPoint
    {
        return $this->mapPoint;
    }

    public function getMapPointLogo(): ?MapPointImage
    {
        return $this->mapPointLogo;
    }

    public function getMapPointImages(): ?MapPointImages
    {
        return $this->mapPointImages;
    }
}