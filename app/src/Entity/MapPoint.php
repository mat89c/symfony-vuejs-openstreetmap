<?php

namespace App\Entity;

use App\Repository\MapPointRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MapPointRepository::class)
 */
class MapPoint
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="map_point.title.not_blank")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="map_point.description.not_blank")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="map_point.lat.not_blank")
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="map_point.lng.not_blank")
     */
    private $lng;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="map_point.color.not_blank")
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="map_point.postcode.not_blank")
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="map_point.city.not_blank")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="map_point.street.not_blank")
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="map_point.logo.not_blank")
     * )
     */
    private $logo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mapPoint")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=MapPointImage::class, mappedBy="mapPoint", cascade={"persist", "remove"})
     */
    private $mapPointImage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uploadDir;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity=MapPointCategory::class, inversedBy="mapPoints", cascade={"persist"})
     */
    private $mapPointCategories;

    public function __construct()
    {
        $this->mapPointImage = new ArrayCollection();
        $this->mapPointCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|MapPointImage[]
     */
    public function getMapPointImage(): Collection
    {
        return $this->mapPointImage;
    }

    public function addMapPointImage(MapPointImage $mapPointImage): self
    {
        if (!$this->mapPointImage->contains($mapPointImage)) {
            $this->mapPointImage[] = $mapPointImage;
            $mapPointImage->setMapPoint($this);
        }

        return $this;
    }

    public function removeMapPointImage(MapPointImage $mapPointImage): self
    {
        if ($this->mapPointImage->contains($mapPointImage)) {
            $this->mapPointImage->removeElement($mapPointImage);
            // set the owning side to null (unless already changed)
            if ($mapPointImage->getMapPoint() === $this) {
                $mapPointImage->setMapPoint(null);
            }
        }

        return $this;
    }

    public function getUploadDir(): ?string
    {
        return $this->uploadDir;
    }

    public function setUploadDir(string $uploadDir): self
    {
        $this->uploadDir = $uploadDir;

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
     * @return Collection|MapPointCategory[]
     */
    public function getMapPointCategories(): Collection
    {
        return $this->mapPointCategories;
    }

    public function addMapPointCategory(MapPointCategory $mapPointCategory): self
    {
        if (!$this->mapPointCategories->contains($mapPointCategory)) {
            $this->mapPointCategories[] = $mapPointCategory;
        }

        return $this;
    }

    public function removeMapPointCategory(MapPointCategory $mapPointCategory): self
    {
        if ($this->mapPointCategories->contains($mapPointCategory)) {
            $this->mapPointCategories->removeElement($mapPointCategory);
        }

        return $this;
    }
}
