<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class BaseUrlService
{
    private $requestStack;

    private $uploadsDir;

    public function __construct(RequestStack $requestStack, string $uploadsDir)
    {
        $this->requestStack = $requestStack;
        $this->uploadsDir = $uploadsDir;
    }

    public function getBaseUrl(): string
    {
        return $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    }

    public function getImageUrl(string $directory, string $file): string
    {
        return $this->getBaseUrl() . '/' .$this->uploadsDir . '/' . $directory . '/' . $file;
    }

    public function getThumbnailUrl(string $directory, string $file): string
    {
        return $this->getBaseUrl() . '/' .$this->uploadsDir . '/' . $directory . '/thumb-' . $file;
    }
}