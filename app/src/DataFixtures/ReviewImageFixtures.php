<?php

namespace App\DataFixtures;

use App\Entity\Review;
use App\Entity\ReviewImage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewImageFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private $images = ['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg'];

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(ReviewImage::class, 100, function(ReviewImage $reviewImage) {
            $reviewImage->setName($this->faker->randomElement($this->images));
            $reviewImage->setReview($this->getRandomReference(Review::class));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ReviewFixtures::class];
    }
}
