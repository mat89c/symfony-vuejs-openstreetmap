<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\MapPoint;
use App\Entity\User;
use App\Entity\MapPointCategory;
use App\Entity\MapPointImage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MapPointFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private $images = ['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg'];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(MapPoint::class, 100, function(MapPoint $mapPoint) {
            $mapPoint->setTitle($this->faker->sentence(3, true));
            $mapPoint->setDescription($this->faker->paragraph(8, true));
            $mapPoint->setLng($this->faker->randomFloat(14, 16, 22));
            $mapPoint->setLat($this->faker->randomFloat(14, 50, 54));
            $mapPoint->setColor($this->faker->hexcolor());
            $mapPoint->setPostcode($this->faker->postcode());
            $mapPoint->setCity($this->faker->city());
            $mapPoint->setStreet($this->faker->streetName());
            $mapPoint->setLogo($this->faker->randomElement($this->images));
            $mapPoint->setUploadDir('fixtures');
            $mapPoint->setIsActive(true);
            $mapPoint->setUser($this->getRandomReference(User::class));
            $mapPoint->setRating(0);
            $mapPoint->setNumberOfReviews(0);

            $rand = rand(1, 5);
            for($i=0; $i <= $rand; $i++) {
                $mapPoint->addMapPointCategory($this->getRandomReference(MapPointCategory::class));
            }
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
