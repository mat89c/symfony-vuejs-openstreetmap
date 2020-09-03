<?php

namespace App\Messenger\Command;

use App\Entity\ReviewImage;

class DeleteReviewImageCommand
{
    private $reviewImage;

    public function __construct(ReviewImage $reviewImage)
    {
        $this->reviewImage = $reviewImage;
    }

    public function getReviewImage(): ReviewImage
    {
        return $this->reviewImage;
    }
}