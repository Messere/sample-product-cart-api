<?php

namespace Messere\Cart\ControllerRequest;

use PHPUnit\Framework\TestCase;

class ProductPaginationRequestTest extends TestCase
{
    /**
     * @dataProvider paginationData
     * @param $inputPage
     * @param int $limit
     * @param int $offset
     * @param int $page
     */
    public function testPagination($inputPage, int $limit, int $offset, int $page): void
    {
        $pagination = new ProductPaginationRequest($inputPage);
        $this->assertEquals($limit, $pagination->getLimit());
        $this->assertEquals($offset, $pagination->getOffset());
        $this->assertEquals($page, $pagination->getPageNumber());
    }

    public function paginationData(): array
    {
        return [
            ['1', 4, 0, 1],
            [null, 4, 0, 1],
            ['aaa', 4, 0, 1],
            [0, 4, 0, 1],
            [-1, 4, 0, 1],
            [2, 4, 3, 2],
            [3, 4, 6, 3],
        ];
    }
}
