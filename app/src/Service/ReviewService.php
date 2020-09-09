<?php

namespace App\Service;

class ReviewService
{
    public function getAvgRating($ratings): float
    {
        if (empty($ratings))
            return 0;

        $numberOfRatings = count($ratings);
        $total = 0;
        foreach ($ratings as $rating) {
            $total +=  is_array($rating) ? $rating['rating'] : $rating->getRating();
        }

        $avg = $total / $numberOfRatings;

        return number_format($avg, 1, ".", "");
    }
}