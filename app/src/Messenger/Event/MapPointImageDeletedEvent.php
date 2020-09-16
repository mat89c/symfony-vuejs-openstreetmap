<?php

namespace App\Messenger\Event;

use App\Entity\MapPointImage;

class MapPointImageDeletedEvent
{
    private $mapPointImage;

    public function __construct(MapPointImage $mapPointImage)
    {
        $this->mapPointImage = $mapPointImage;
    }

    public function getMapPointImage(): MapPointImage
    {
        return $this->mapPointImage;
    }
}