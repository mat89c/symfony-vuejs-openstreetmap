<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MapPoint::class, inversedBy="reviews")
     * @Assert\NotBlank(message="review.map_point.not_blank")
     */
    private $mapPoint;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @Assert\NotBlank(message="review.user.not_blank")
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank(message="review.rating.not_blank")
     * @Assert\GreaterThan(value=0, message="review.rating.greater_than")
     * @Assert\LessThan(value=11, message="review.rating.less_than")
     */
    private $rating;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=ReviewImage::class, mappedBy="review", cascade={"persist"})
     */
    private $reviewImages;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->reviewImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
     * @return Collection|ReviewImage[]
     */
    public function getReviewImages(): Collection
    {
        return $this->reviewImages;
    }

    public function addReviewImage(ReviewImage $reviewImage): self
    {
        if (!$this->reviewImages->contains($reviewImage)) {
            $this->reviewImages[] = $reviewImage;
            $reviewImage->setReview($this);
        }

        return $this;
    }

    public function removeReviewImage(ReviewImage $reviewImage): self
    {
        if ($this->reviewImages->contains($reviewImage)) {
            $this->reviewImages->removeElement($reviewImage);
            // set the owning side to null (unless already changed)
            if ($reviewImage->getReview() === $this) {
                $reviewImage->setReview(null);
            }
        }

        return $this;
    }
}
