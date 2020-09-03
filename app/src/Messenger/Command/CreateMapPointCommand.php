<?php

namespace App\Messenger\Command;

use App\Entity\MapPoint;
use App\Helper\MapPointImage;
use App\Helper\MapPointImages;

class CreateMapPointCommand
{
    private $mapPoint;

    private $mapPointLogo;

    private $mapPointImages;

    public function __construct(MapPoint $mapPoint, MapPointImage $mapPointLogo, MapPointImages $mapPointImages)
    {
        $this->mapPoint = $mapPoint;
        $this->mapPointLogo = $mapPointLogo;
        $this->mapPointImages = $mapPointImages;
    }

    public function getMapPoint(): MapPoint
    {
        return $this->mapPoint;
    }

    public function getMapPointLogo(): MapPointImage
    {
        return $this->mapPointLogo;
    }

    public function getMapPointImages(): MapPointImages
    {
        return $this->mapPointImages;
    }
}