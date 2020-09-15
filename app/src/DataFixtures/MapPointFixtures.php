<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\MapPoint;
use App\Entity\User;
use App\Entity\MapPointCategory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MapPointFixtures extends BaseFixtures implements DependentFixtureInterface
{
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
            $mapPoint->setLogo(uniqid() . '.jpg');
            $mapPoint->setUploadDir(uniqid());
            $mapPoint->setIsActive($this->faker->numberBetween(0,1));
            $mapPoint->setUser($this->getRandomReference(User::class));
            $mapPoint->setRating(0);
            $mapPoint->setNumberOfReviews(0);

            $this->generateImages($mapPoint->getLogo(), $mapPoint->getUploadDir());

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
