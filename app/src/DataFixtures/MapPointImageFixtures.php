<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\MapPointImage;
use App\Entity\MapPoint;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MapPointImageFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(MapPointImage::class, 200, function(MapPointImage $mapPointImage) {
            $mapPoint = $this->getRandomReference(MapPoint::class);
            $mapPointImage->setName(uniqid() . 'jpg');
            $mapPointImage->setMapPoint($mapPoint);

            $this->generateImages($mapPointImage->getName(), $mapPoint->getUploadDir());
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [MapPointFixtures::class];
    }
}
