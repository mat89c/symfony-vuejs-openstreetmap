<?php

namespace App\Messenger\Event;

use Doctrine\Common\Collections\Collection;

class ReviewMapPointChangedEvent
{
    private $reviewImages;

    private $oldImagesDirectory;

    private $newImagesDirectory;

    public function __construct(?Collection $reviewImages, string $oldImagesDirectory, string $newImagesDirectory)
    {
        $this->reviewImages = $reviewImages;
        $this->oldImagesDirectory = $oldImagesDirectory;
        $this->newImagesDirectory = $newImagesDirectory;
    }

    public function getReviewImages(): ?Collection
    {
        return $this->reviewImages;
    }

    public function getOldImagesDirectory(): string
    {
        return $this->oldImagesDirectory;
    }

    public function getNewImagesDirectiory(): string
    {
        return $this->newImagesDirectory;
    }

}