<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Review;
use App\Entity\User;
use App\Entity\MapPoint;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\ReviewService;

class ReviewFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Review::class, 100, function(Review $review) {
            $rating = $this->faker->numberBetween(1, 10);
            $mapPoint = $this->getRandomReference(MapPoint::class);

            $review->setContent($this->faker->sentence(10, true));
            $review->setRating($rating);
            $review->setUser($this->getRandomReference(User::class));
            $review->setIsActive($this->faker->numberBetween(0,1));
            $review->setMapPoint($mapPoint);

            $numberOfReviews = $mapPoint->getNumberOfReviews() + 1;
            $mapPoint->addReview($review);
            $mapPoint->setNumberOfReviews($numberOfReviews);
            $mapPoint->setRating($this->reviewService->getAvgRating($mapPoint->getReviews()));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [MapPointFixtures::class];
    }
}
