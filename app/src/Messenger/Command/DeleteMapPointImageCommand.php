<?php

namespace App\Messenger\Command;

use App\Entity\MapPointImage;

class DeleteMapPointImageCommand
{
    private $mapPointImage;

    public function __construct(MapPointImage $mapPointImage)
    {
        $this->mapPointImage = $mapPointImage;
    }

    public function getMapPointImage()
    {
        return $this->mapPointImage;
    }
}