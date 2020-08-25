<?php

namespace App\Helper;
use App\Helper\MapPointFile;

class MapPointFiles
{
    private $files = [];

    public function __construct(?array $uploadedFiles)
    {
        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                $this->files[] = new MapPointFile($file);
            }
        }
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}