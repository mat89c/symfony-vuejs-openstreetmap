<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\MapPointImage;

class MapPointImageFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $mapPointImage = new MapPointImage();
        $mapPointImage->setName('1.jpg');
        $manager->persist($mapPointImage);
        $this->addReference('App\Entity\MapPointImage_0', $mapPointImage);

        $mapPointImage = new MapPointImage();
        $mapPointImage->setName('2.jpg');
        $manager->persist($mapPointImage);
        $this->addReference('App\Entity\MapPointImage_1', $mapPointImage);

        $mapPointImage = new MapPointImage();
        $mapPointImage->setName('3.jpg');
        $manager->persist($mapPointImage);
        $this->addReference('App\Entity\MapPointImage_2', $mapPointImage);

        $mapPointImage = new MapPointImage();
        $mapPointImage->setName('4.jpg');
        $manager->persist($mapPointImage);
        $this->addReference('App\Entity\MapPointImage_3', $mapPointImage);

        $mapPointImage = new MapPointImage();
        $mapPointImage->setName('5.jpg');
        $manager->persist($mapPointImage);
        $this->addReference('App\Entity\MapPointImage_4', $mapPointImage);

        $mapPointImage = new MapPointImage();
        $mapPointImage->setName('6.jpg');
        $manager->persist($mapPointImage);
        $this->addReference('App\Entity\MapPointImage_5', $mapPointImage);

        $mapPointImage = new MapPointImage();
        $mapPointImage->setName('7.jpg');
        $manager->persist($mapPointImage);
        $this->addReference('App\Entity\MapPointImage_6', $mapPointImage);

        $manager->flush();
    }
}
