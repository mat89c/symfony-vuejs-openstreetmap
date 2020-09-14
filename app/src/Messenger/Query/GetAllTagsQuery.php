<?php

namespace App\Messenger\Query;

class GetAllTagsQuery
{
    private $page;

    private $status;

    public function __construct(int $page, ?bool $status)
    {
        $this->page = $page;
        $this->status = $status;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }
}