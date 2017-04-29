<?php


namespace Rundiz\Pagination\Tests;

class PaginationTest extends \PHPUnit\Framework\TestCase
{


    public function testPagination()
    {
        $total_records = 1000;
        $start = 0;
        $limit = 10;
        $base_url = 'http://localhost/pagination?start=%PAGENUMBER%';

        $Pagination = new \Rundiz\Pagination\Pagination();
        $Pagination->base_url = $base_url;
        $Pagination->total_records = $total_records;
        $Pagination->page_number_value = $start;
        $pagination_data = $Pagination->getPaginationData();
        $Pagination->clear();
        unset($Pagination);

        // assert
        $this->assertArrayHasKey('total_pages', $pagination_data);
        $this->assertArrayHasKey('page_number_type', $pagination_data);
        $this->assertArrayHasKey('current_page_number_displaying', $pagination_data);
        $this->assertArrayHasKey('generated_pages', $pagination_data);
        $this->assertEquals(100, intval($pagination_data['total_pages']));
        $this->assertEquals('start_num', $pagination_data['page_number_type']);
        $this->assertEquals(1, intval($pagination_data['current_page_number_displaying']));
        $this->assertTrue(is_array($pagination_data['generated_pages']));
        $this->assertCount(10, $pagination_data['generated_pages']);
    }// testPagination


}
