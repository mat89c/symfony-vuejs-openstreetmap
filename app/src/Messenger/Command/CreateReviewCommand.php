<?php

namespace App\Messenger\Command;

use App\Entity\Review;
use App\Helper\ReviewImages;

class CreateReviewCommand
{
    private $review;

    private $reviewImages;

    public function __construct(Review $review, ReviewImages $reviewImages)
    {
        $this->review = $review;
        $this->reviewImages = $reviewImages;
    }

    public function getReview(): Review
    {
        return $this->review;
    }

    public function getReviewImages(): ReviewImages
    {
        return $this->reviewImages;
    }
}