<?php

namespace App\Messenger\Command;

use App\Entity\MapPoint;
use App\Helper\MapPointFile;
use App\Helper\MapPointFiles;

class CreateMapPointCommand
{
    private $mapPoint;

    private $mapPointLogo;

    private $mapPointImages;

    public function __construct(MapPoint $mapPoint, MapPointFile $mapPointLogo, MapPointFiles $mapPointImages)
    {
        $this->mapPoint = $mapPoint;
        $this->mapPointLogo = $mapPointLogo;
        $this->mapPointImages = $mapPointImages;
    }

    public function getMapPoint(): MapPoint
    {
        return $this->mapPoint;
    }

    public function getMapPointLogo(): MapPointFile
    {
        return $this->mapPointLogo;
    }

    public function getMapPointImages(): MapPointFiles
    {
        return $this->mapPointImages;
    }
}