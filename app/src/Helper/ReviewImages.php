<?php

namespace App\Helper;
use App\Helper\ReviewImage;

class ReviewImages implements ImagesInterface
{
    private $images = [];

    public function __construct(?array $uploadedImages)
    {
        if (!empty($uploadedImages)) {
            foreach ($uploadedImages as $image) {
                $this->images[] = new ReviewImage($image);
            }
        }
    }

    public function getImages(): array
    {
        return $this->images;
    }
}