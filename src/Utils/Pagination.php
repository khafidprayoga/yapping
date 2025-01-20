<?php

namespace Khafidprayoga\PhpMicrosite\Utils;

readonly class Pagination
{
    private const int DEFAULT_PAGE_SIZE = 10;
    private const int DEFAULT_PAGE_NUMBER = 1;
    private int $page;
    private int $pageSize;
    private string $search;

    public function __construct(array $request)
    {
        $this->page = $request['page'] ?? self::DEFAULT_PAGE_NUMBER;
        $this->pageSize = $request['page_size'] ?? self::DEFAULT_PAGE_SIZE;
        $this->search = $request['search'] ?? '';
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
}
