<?php

namespace Khafidprayoga\PhpMicrosite\Utils;

class Pagination
{
    private int $page = 1;
    private int $pageSize = 10;
    private string $search = '';
    private ?int $previousId = null;
    private ?int $nextId = null;

    public function __construct(array $request)
    {

        if (isset($request['page'])) {
            $this->page = (int )$request['page'];
        }

        if (isset($request['pageSize'])) {
            $this->pageSize = (int )$request['pageSize'];
        }

        if (isset($request['search'])) {
            $this->search = $request['search'];
        }
        if (isset($request['previousId'])) {
            $this->previousId = $request['previousId'];
        }
        if (isset($request['nextId'])) {
            $this->nextId = $request['nextId'];
        }
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getSearch(): string
    {
        return $this->search;
    }

    // allow search if char > 3
    public function isContainsSearch(): bool
    {
        return strlen($this->search) > 3;
    }

    public function getOffset(): int
    {
        return ($this->getPage() - 1) * $this->getPageSize();
    }

    public function getPreviousId(): ?int
    {
        return $this->previousId;
    }

    public function getNextId(): ?int
    {
        return $this->nextId;
    }
}
