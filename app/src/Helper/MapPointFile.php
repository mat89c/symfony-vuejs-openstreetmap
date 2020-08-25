<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MapPointFile
{
    private $name;

    private $file;

    public function __construct(UploadedFile $file)
    {
        $this->name = uniqid() . '.' . $file->getClientOriginalExtension();
        $this->file = $file;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}