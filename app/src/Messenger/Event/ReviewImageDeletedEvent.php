<?php

namespace App\Messenger\Event;

use App\Entity\ReviewImage;

class ReviewImageDeletedEvent
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