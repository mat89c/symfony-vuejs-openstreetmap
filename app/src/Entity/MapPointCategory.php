<?php

namespace App\Entity;

use App\Repository\MapPointCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MapPointCategoryRepository::class)
 */
class MapPointCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity=MapPoint::class, mappedBy="mapPointCategories")
     */
    private $mapPoints;

    public function __construct()
    {
        $this->mapPoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|MapPoint[]
     */
    public function getMapPoints(): Collection
    {
        return $this->mapPoints;
    }

    public function addMapPoint(MapPoint $mapPoint): self
    {
        if (!$this->mapPoints->contains($mapPoint)) {
            $this->mapPoints[] = $mapPoint;
            $mapPoint->addMapPointCategory($this);
        }

        return $this;
    }

    public function removeMapPoint(MapPoint $mapPoint): self
    {
        if ($this->mapPoints->contains($mapPoint)) {
            $this->mapPoints->removeElement($mapPoint);
            $mapPoint->removeMapPointCategory($this);
        }

        return $this;
    }
}
