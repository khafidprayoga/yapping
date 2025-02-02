<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Khafidprayoga\PhpMicrosite\Utils\Pagination;

class FeedsRequestDTO extends Pagination
{

    private int $authorId = 0;
    private string $authorName = "";

    public function __construct(array $request)
    {
        parent::__construct($request);

        if (isset($request['author_id'])) {
            $this->authorId = $request['author_id'];
        }

        if (isset($request['author_name'])) {
            $this->authorName = $request['author_name'];
        }
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getAuthorName(): string
    {
        return $this->authorName ;
    }
}