<?php

namespace App\Entity;

use App\Repository\MapPointImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MapPointImageRepository::class)
 */
class MapPointImage
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
     * @ORM\ManyToOne(targetEntity=MapPoint::class, inversedBy="mapPointImage")
     */
    private $mapPoint;

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

    public function getMapPoint(): ?MapPoint
    {
        return $this->mapPoint;
    }

    public function setMapPoint(?MapPoint $mapPoint): self
    {
        $this->mapPoint = $mapPoint;

        return $this;
    }
}
