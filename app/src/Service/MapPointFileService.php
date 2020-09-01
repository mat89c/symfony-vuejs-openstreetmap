<?php

namespace App\Service;

use App\Entity\MapPoint;
use App\Helper\MapPointFile;
use App\Helper\MapPointFiles;
use Intervention\Image\ImageManager;

class MapPointFileService
{
    private $uploadsPath;

    private $imageManager;

    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
        $this->imageManager = new ImageManager();
    }

    public function handleLogo(MapPoint $mapPoint, MapPointFile $mapPointLogo): void
    {
        $mapPointLogo->getFile()->move($this->uploadsPath . '/' . $mapPoint->getUploadDir(),  $mapPointLogo->getName());

        $image = $this->imageManager->make($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . $mapPointLogo->getName());
        $image->resize(1300, 1300, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . $mapPointLogo->getName());

        $image->resize(300, 300, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . 'thumb-' . $mapPointLogo->getName());
    }

    public function handleImages(MapPoint $mapPoint, MapPointFiles $mapPointImages): void
    {
        foreach ($mapPointImages->getFiles() as $item) {
            $item->getFile()->move($this->uploadsPath . '/' . $mapPoint->getUploadDir(), $item->getName());

            $image = $this->imageManager->make($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . $item->getName());
            $image->resize(1920, 1920, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . $item->getName());

            $image = $this->imageManager->make($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . $item->getName());
            $image->resize(768, 768, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . 'thumb-' . $item->getName());
        }
    }
}