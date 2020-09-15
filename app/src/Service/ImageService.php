<?php

namespace App\Service;

use App\Entity\MapPoint;
use App\Helper\ImageInterface;
use App\Helper\ImagesInterface;
use Intervention\Image\ImageManager;
use Symfony\Component\Filesystem\Filesystem;

class ImageService
{
    private $uploadsPath;

    private $imageManager;

    private $filesystem;

    public function __construct(string $uploadsPath, Filesystem $filesystem)
    {
        $this->uploadsPath = $uploadsPath;
        $this->imageManager = new ImageManager();
        $this->filesystem = $filesystem;
    }

    public function handleImage(MapPoint $mapPoint, ImageInterface $uploadedImage): void
    {
        $uploadedImage->getImage()->move($this->uploadsPath . '/' . $mapPoint->getUploadDir(),  $uploadedImage->getName());

        $image = $this->imageManager->make($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . $uploadedImage->getName());
        $image->resize(1300, 1300, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . $uploadedImage->getName());

        $image->resize(300, 300, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . 'thumb-' . $uploadedImage->getName());
    }

    public function handleImages(MapPoint $mapPoint, ImagesInterface $uploadedImages): void
    {
        foreach ($uploadedImages->getImages() as $item) {
            $item->getImage()->move($this->uploadsPath . '/' . $mapPoint->getUploadDir(), $item->getName());

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

    public function deleteImageFile(MapPoint $mapPoint, string $imageName): void
    {
        $this->filesystem->remove($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/' . $imageName);
        $this->filesystem->remove($this->uploadsPath . '/' . $mapPoint->getUploadDir() . '/thumb-' . $imageName);
    }

    public function deleteImageDirectoryWithImages(MapPoint $mapPoint): void
    {
        $this->filesystem->remove($this->uploadsPath . '/' . $mapPoint->getUploadDir());
    }

    public function moveImageToNewDirectory(string $imageName, string $oldDir, string $newDir)
    {
        if ($this->filesystem->exists($this->uploadsPath . '/' . $oldDir . '/' . $imageName))
            $this->filesystem->rename($this->uploadsPath . '/' . $oldDir . '/' . $imageName, $this->uploadsPath . '/' . $newDir . '/' . $imageName, true);

        if ($this->filesystem->exists($this->uploadsPath . '/' . $oldDir . '/thumb-' . $imageName))
            $this->filesystem->rename($this->uploadsPath . '/' . $oldDir . '/thumb-' . $imageName, $this->uploadsPath . '/' . $newDir . '/thumb-' . $imageName, true);

    }
}