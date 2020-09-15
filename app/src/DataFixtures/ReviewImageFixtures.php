<?php

namespace App\DataFixtures;

use App\Entity\Review;
use App\Entity\ReviewImage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewImageFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(ReviewImage::class, 100, function(ReviewImage $reviewImage) {
            $review = $this->getRandomReference(Review::class);
            $reviewImage->setName(uniqid() . '.jpg');
            $reviewImage->setReview($review);

            $this->generateImages($reviewImage->getName(), $review->getMapPoint()->getUploadDir());
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ReviewFixtures::class];
    }
}
