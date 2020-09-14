<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\MapPointImage;
use App\Entity\MapPoint;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MapPointImageFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private $images = ['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg'];

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(MapPointImage::class, 200, function(MapPointImage $mapPointImage) {
            $mapPointImage->setName($this->faker->randomElement($this->images));
            $mapPointImage->setMapPoint($this->getRandomReference(MapPoint::class));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [MapPointFixtures::class];
    }
}
