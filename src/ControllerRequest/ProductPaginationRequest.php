<?php

namespace Messere\Cart\ControllerRequest;

class ProductPaginationRequest
{
    public const PAGE_SIZE = 3;

    private $offset;
    private $limit;
    private $pageNumber;

    public function __construct($pageNumber)
    {
        $pageNumber = (int)$pageNumber - 1;
        if ($pageNumber < 0) {
            $pageNumber = 0;
        }

        $this->pageNumber = $pageNumber + 1;
        $this->limit = static::PAGE_SIZE + 1;
        $this->offset = $pageNumber * static::PAGE_SIZE;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }
}
