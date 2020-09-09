<?php

namespace App\DataFixtures;

use App\Entity\MapPointCategory;
use Doctrine\Persistence\ObjectManager;

class MapPointCategoryFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('molo');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_0', $category);

        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('płatny parking');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_1', $category);

        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('darmowy parking');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_2', $category);

        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('wypożyczalnia sprzętu wodnego');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_3', $category);

        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('toalety');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_4', $category);

        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('prysznice');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_5', $category);

        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('pole namiotowe');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_6', $category);

        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('pomost');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_7', $category);

        $category = new MapPointCategory();
        $category->setIsActive(true);
        $category->setName('wędkowanie');
        $manager->persist($category);
        $this->addReference('App\Entity\MapPointCategory_8', $category);

        $manager->flush();
    }
}
