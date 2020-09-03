<?php

namespace App\Helper;
use App\Helper\MapPointImage;

class MapPointImages implements ImagesInterface
{
    private $images = [];

    public function __construct(?array $uploadedImages)
    {
        if (!empty($uploadedImages)) {
            foreach ($uploadedImages as $image) {
                $this->images[] = new MapPointImage($image);
            }
        }
    }

    public function getImages(): array
    {
        return $this->images;
    }
}