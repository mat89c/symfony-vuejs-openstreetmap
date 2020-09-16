<?php

namespace App\Messenger\Query;

class GetMapPointByIdQuery
{
    private $id;

    private $filters;

    public function __construct(int $id, ?array $filters = null)
    {
        $this->id = $id;
        $this->filters = $filters;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFilters(): ?array
    {
        return $this->filters;
    }
}