<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageInterface
{
    public function getName(): string;

    public function getImage(): UploadedFile;
}
