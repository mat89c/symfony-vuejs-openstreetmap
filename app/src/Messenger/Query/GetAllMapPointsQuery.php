<?php

namespace App\Messenger\Query;

class GetAllMapPointsQuery
{
    private $page;

    private $status;

    public function __construct(int $page, ?int $status)
    {
        $this->page = $page;
        $this->status = $status;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }
}