<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class BaseUrlService
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getBaseUrl(): string
    {
        return $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    }
}