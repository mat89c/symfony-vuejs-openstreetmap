<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Filesystem\Filesystem;

abstract class BaseFixtures extends Fixture
{
    private $manager;

    protected $faker;

    private $referencesIndex = [];

    private $images = ['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg'];

    private $filesystem;

    private $imgFixturesPath;

    private $uploadsPath;

    public function __construct(Filesystem $filesystem, string $imgFixturesPath, string $uploadsPath)
    {
        $this->filesystem = $filesystem;
        $this->imgFixturesPath = $imgFixturesPath;
        $this->uploadsPath = $uploadsPath;
    }

    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create('pl_PL');

        $this->loadData($manager);
    }

    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);

            $this->addReference($className . '_' . $i, $entity);
        }
    }

    protected function getRandomReference(string $className) {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className.'_') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$className])) {
            throw new \Exception(sprintf('Cannot find any references for class "%s"', $className));
        }
        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$className]);
        return $this->getReference($randomReferenceKey);
    }

    protected function generateImages(string $imageName, string $directoryName): void
    {
        $randImage = $this->faker->randomElement($this->images);
        if ($this->filesystem->exists($this->imgFixturesPath . '/' . $randImage)) {
            $this->filesystem->copy(
                $this->imgFixturesPath . '/' . $randImage,
                $this->uploadsPath . '/' . $directoryName . '/' . $imageName
            );
        }

        if ($this->filesystem->exists($this->imgFixturesPath . '/thumb-' . $randImage)) {
            $this->filesystem->copy(
                $this->imgFixturesPath . '/thumb-' . $randImage,
                $this->uploadsPath . '/' . $directoryName . '/thumb-' . $imageName
            );
        }
    }
}
