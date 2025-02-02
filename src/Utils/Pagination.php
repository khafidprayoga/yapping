<?php

namespace Khafidprayoga\PhpMicrosite\Utils;

use Carbon\Carbon;
use Doctrine\DBAL\Exception;

class Pagination
{
    private int $page = 1;
    private int $pageSize = 10;
    private string $search = '';
    private ?int $previousId = null;
    private ?int $nextId = null;
    private bool $calculateCountOnDB = true;
    private ?int $totalPages = null;
    private ?int $totalItems = null;

    private ?Carbon $startDate = null;
    private ?Carbon $endDate = null;

    public function __construct(array $request)
    {
        if (isset($request['page'])) {
            $this->page = (int)$request['page'];
        }

        if (isset($request['page_size'])) {
            $this->pageSize = (int)$request['page_size'];
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

        if (isset($request['total_items']) && isset($request['total_pages'])) {
            $this->calculateCountOnDB = false;
            $this->totalPages = $request['total_pages'];
            $this->totalItems = $request['total_items'];
        }

        if (isset($request['start_date']) && isset($request['end_date'])) {
            if ($request['start_date'] !== '' || $request['end_date'] !== '') {
                $start = Carbon::parse($request['start_date']);
                $end = Carbon::parse($request['end_date'])->addDays(1);

                $this->startDate = $start;
                $this->endDate = $end;
            }
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

    public function getTotalItems(): ?int
    {
        return $this->totalItems;
    }

    public function getTotalPages(): ?int
    {
        return $this->totalPages;
    }

    public function isNeedCountOnDB(): bool
    {
        return $this->calculateCountOnDB;
    }

    public function getStartDate(): ?Carbon
    {
        return $this->startDate;
    }

    public function getStartDateStr(): string
    {
        if (!is_null($this->startDate)) {
            return $this->startDate->format('Y-m-d');
        }
        return "";
    }

    public function getEndDate(): ?Carbon
    {
        return $this->endDate;
    }

    public function getEndDateStr(): string
    {
        if (!is_null($this->endDate)) {
            return $this->endDate->addDays(-1)->format('Y-m-d');
        }
        return "";
    }
}
