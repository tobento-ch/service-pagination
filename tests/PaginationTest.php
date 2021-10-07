<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Pagination\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Pagination\Pagination;
use Tobento\Service\Pagination\PaginationInterface;
use Tobento\Service\Pagination\UrlGenerator;
use Tobento\Service\Pagination\MenuRenderer;

/**
 * PaginationTest tests
 */
class PaginationTest extends TestCase
{    
    public function testThatPaginationImplementsPaginationInterface()
    {
        $this->assertInstanceOf(
            PaginationInterface::class,
            new Pagination(totalItems: 200)
        );     
    }
    
    public function testPaginationConstructorParameters()
    {
        $pagination = new Pagination(
            totalItems: 200,
            currentPage: 3,
            itemsPerPage: 50,
            maxPagesToShow: 10,
            maxItemsPerPage: 1000,
            urlGenerator: new UrlGenerator(),
            renderer: new MenuRenderer(),
        );
        
        $this->assertInstanceOf(
            PaginationInterface::class,
            $pagination
        );        
    }
    
    public function testWithCurrentPageMethod()
    {
        $pagination = new Pagination(totalItems: 200);

        $newPagination = $pagination->withCurrentPage(6);

        $this->assertFalse($pagination === $newPagination);
        
        $this->assertSame(6, $newPagination->getCurrentPage());
    }
    
    public function testWithItemsPerPageMethod()
    {
        $pagination = new Pagination(totalItems: 200);

        $newPagination = $pagination->withItemsPerPage(30);

        $this->assertFalse($pagination === $newPagination);
        
        $this->assertSame(30, $newPagination->getItemsPerPage());
    }
    
    public function testWithMaxPagesToShowMethod()
    {
        $pagination = new Pagination(
            totalItems: 100,
            currentPage: 5,
            itemsPerPage: 10,
            maxPagesToShow: 10,
        );

        $newPagination = $pagination->withMaxPagesToShow(3);

        $this->assertFalse($pagination === $newPagination);
        
        $this->assertSame(5, $newPagination->getTotalPages());
    }
    
    public function testWithUrlGeneratorMethod()
    {
        $pagination = new Pagination(totalItems: 200);

        $newPagination = $pagination->withUrlGenerator(
            (new UrlGenerator())->addPageUrl('#{num}')
        );

        $this->assertFalse($pagination === $newPagination);
        
        $this->assertSame('#1', $newPagination->getPages()[1]->url());
    }
    
    public function testWithRendererMethod()
    {
        $pagination = new Pagination(
            totalItems: 200,
            currentPage: 7,
            itemsPerPage: 10,
        );

        $newPagination = $pagination->withRenderer(new MenuRenderer('prev'));

        $this->assertFalse($pagination === $newPagination);
        
        $this->assertNotSame(
            (string)$pagination,
            (string)$newPagination
        );
    }
    
    public function testMaxItemsPerPage()
    {
        $pagination = new Pagination(
            totalItems: 2000,
            currentPage: 3,
            itemsPerPage: 1500,
            maxPagesToShow: 10,
            maxItemsPerPage: 1000,
        );
        
        $this->assertSame(
            1000,
            $pagination->getItemsPerPage()
        );
    }    

    /**
     * @dataProvider getTestData
     */    
    public function testPaginationCalc(
        $totalItems,
        $currentPage,
        $itemsPerPage,
        $maxPagesToShow,
        $expected,
    ) {
        $pagination = new Pagination(
            totalItems: $totalItems,
            currentPage: $currentPage,
            itemsPerPage: $itemsPerPage,
            maxPagesToShow: $maxPagesToShow,
            maxItemsPerPage: 1000,
        );
        
        $pageNames = [];
        
        foreach($pagination->getPages() as $page)
        {
            $pageNames[] = $page->name();
        }
        
        $this->assertEquals(
            $expected,
            [
                'pageNames' => $pageNames,
                'getTotalItems' => $pagination->getTotalItems(),
                'getTotalItemsFrom' => $pagination->getTotalItemsFrom(),
                'getTotalItemsTo' => $pagination->getTotalItemsTo(),
                'getItemsPerPage' => $pagination->getItemsPerPage(),
                'getItemsOffset' => $pagination->getItemsOffset(),
                'hasPages' => $pagination->hasPages(),
                'hasCurrentPage' => $pagination->hasCurrentPage(),
                'getCurrentPage' => $pagination->getCurrentPage(),
                'getPrevPageUrl' => $pagination->getPrevPageUrl(),
                'getNextPageUrl' => $pagination->getNextPageUrl(),
            ]
        );
    }
  
    public function getTestData()
    {
        return [
            0 => [
                'totalItems' => 200,
                'currentPage' => 1,
                'itemsPerPage' => 50,
                'maxPagesToShow' => 8,
                'expected' => [
                    'pageNames' => ['1', '2', '3', '4'],
                    'getTotalItems' => 200,
                    'getTotalItemsFrom' => 1,
                    'getTotalItemsTo' => 50,
                    'getItemsPerPage' => 50,
                    'getItemsOffset' => 0,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 1,
                    'getPrevPageUrl' => null,
                    'getNextPageUrl' => 'page/2',
                ],
            ],
            1 => [
                'totalItems' => 200,
                'currentPage' => 1,
                'itemsPerPage' => 20,
                'maxPagesToShow' => 8,
                'expected' => [
                    'pageNames' => ['1', '2', '3', '4', '5', '6', '7', '...', '10'],
                    'getTotalItems' => 200,
                    'getTotalItemsFrom' => 1,
                    'getTotalItemsTo' => 20,
                    'getItemsPerPage' => 20,
                    'getItemsOffset' => 0,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 1,
                    'getPrevPageUrl' => null,
                    'getNextPageUrl' => 'page/2',
                ],
            ],
            2 => [
                'totalItems' => 200,
                'currentPage' => 6,
                'itemsPerPage' => 20,
                'maxPagesToShow' => 8,
                'expected' => [
                    'pageNames' => ['1', '...', '4', '5', '6', '7', '8', '9', '10'],
                    'getTotalItems' => 200,
                    'getTotalItemsFrom' => 101,
                    'getTotalItemsTo' => 120,
                    'getItemsPerPage' => 20,
                    'getItemsOffset' => 100,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 6,
                    'getPrevPageUrl' => 'page/5',
                    'getNextPageUrl' => 'page/7',
                ],
            ],
            3 => [
                'totalItems' => 200,
                'currentPage' => 10,
                'itemsPerPage' => 20,
                'maxPagesToShow' => 8,
                'expected' => [
                    'pageNames' => ['1', '...', '4', '5', '6', '7', '8', '9', '10'],
                    'getTotalItems' => 200,
                    'getTotalItemsFrom' => 181,
                    'getTotalItemsTo' => 200,
                    'getItemsPerPage' => 20,
                    'getItemsOffset' => 180,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 10,
                    'getPrevPageUrl' => 'page/9',
                    'getNextPageUrl' => null,
                ],
            ],
            4 => [
                'totalItems' => 200,
                'currentPage' => 5,
                'itemsPerPage' => 20,
                'maxPagesToShow' => 8,
                'expected' => [
                    'pageNames' => ['1', '...', '3', '4', '5', '6', '7', '8', '...', '10'],
                    'getTotalItems' => 200,
                    'getTotalItemsFrom' => 81,
                    'getTotalItemsTo' => 100,
                    'getItemsPerPage' => 20,
                    'getItemsOffset' => 80,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 5,
                    'getPrevPageUrl' => 'page/4',
                    'getNextPageUrl' => 'page/6',
                ],
            ],
            5 => [
                'totalItems' => 13,
                'currentPage' => 2,
                'itemsPerPage' => 1,
                'maxPagesToShow' => 5,
                'expected' => [
                    'pageNames' => ['1', '2', '3', '4', '...', '13'],
                    'getTotalItems' => 13,
                    'getTotalItemsFrom' => 2,
                    'getTotalItemsTo' => 2,
                    'getItemsPerPage' => 1,
                    'getItemsOffset' => 1,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 2,
                    'getPrevPageUrl' => 'page/1',
                    'getNextPageUrl' => 'page/3',
                ],
            ],
            6 => [
                'totalItems' => 13,
                'currentPage' => 4,
                'itemsPerPage' => 1,
                'maxPagesToShow' => 5,
                'expected' => [
                    'pageNames' => ['1', '...', '3', '4', '5', '...', '13'],
                    'getTotalItems' => 13,
                    'getTotalItemsFrom' => 4,
                    'getTotalItemsTo' => 4,
                    'getItemsPerPage' => 1,
                    'getItemsOffset' => 3,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 4,
                    'getPrevPageUrl' => 'page/3',
                    'getNextPageUrl' => 'page/5',
                ],
            ],
            7 => [
                'totalItems' => 13,
                'currentPage' => 5,
                'itemsPerPage' => 1,
                'maxPagesToShow' => 5,
                'expected' => [
                    'pageNames' => ['1', '...', '4', '5', '6', '...', '13'],
                    'getTotalItems' => 13,
                    'getTotalItemsFrom' => 5,
                    'getTotalItemsTo' => 5,
                    'getItemsPerPage' => 1,
                    'getItemsOffset' => 4,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 5,
                    'getPrevPageUrl' => 'page/4',
                    'getNextPageUrl' => 'page/6',
                ],
            ],
            8 => [
                'totalItems' => 13,
                'currentPage' => 11,
                'itemsPerPage' => 1,
                'maxPagesToShow' => 5,
                'expected' => [
                    'pageNames' => ['1', '...', '10', '11', '12', '13'],
                    'getTotalItems' => 13,
                    'getTotalItemsFrom' => 11,
                    'getTotalItemsTo' => 11,
                    'getItemsPerPage' => 1,
                    'getItemsOffset' => 10,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 11,
                    'getPrevPageUrl' => 'page/10',
                    'getNextPageUrl' => 'page/12',
                ],
            ],
            9 => [
                'totalItems' => 5,
                'currentPage' => 3,
                'itemsPerPage' => 1,
                'maxPagesToShow' => 10,
                'expected' => [
                    'pageNames' => ['1', '2', '3', '4', '5'],
                    'getTotalItems' => 5,
                    'getTotalItemsFrom' => 3,
                    'getTotalItemsTo' => 3,
                    'getItemsPerPage' => 1,
                    'getItemsOffset' => 2,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 3,
                    'getPrevPageUrl' => 'page/2',
                    'getNextPageUrl' => 'page/4',
                ],
            ],
            
            // Only one page
            10 => [
                'totalItems' => 1,
                'currentPage' => 1,
                'itemsPerPage' => 1,
                'maxPagesToShow' => 10,
                'expected' => [
                    'pageNames' => ['1'],
                    'getTotalItems' => 1,
                    'getTotalItemsFrom' => 1,
                    'getTotalItemsTo' => 1,
                    'getItemsPerPage' => 1,
                    'getItemsOffset' => 0,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 1,
                    'getPrevPageUrl' => null,
                    'getNextPageUrl' => null,
                ],
            ],          
            11 => [
                'totalItems' => 50,
                'currentPage' => 1,
                'itemsPerPage' => 100,
                'maxPagesToShow' => 10,
                'expected' => [
                    'pageNames' => ['1'],
                    'getTotalItems' => 50,
                    'getTotalItemsFrom' => 1,
                    'getTotalItemsTo' => 50,
                    'getItemsPerPage' => 100,
                    'getItemsOffset' => 0,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 1,
                    'getPrevPageUrl' => null,
                    'getNextPageUrl' => null,
                ],
            ],
            
            // 0 items
            12 => [
                'totalItems' => 0,
                'currentPage' => 1,
                'itemsPerPage' => 200,
                'maxPagesToShow' => 10,
                'expected' => [
                    'pageNames' => [],
                    'getTotalItems' => 0,
                    'getTotalItemsFrom' => 0,
                    'getTotalItemsTo' => 0,
                    'getItemsPerPage' => 200,
                    'getItemsOffset' => 0,
                    'hasPages' => false,
                    'hasCurrentPage' => false,
                    'getCurrentPage' => 1,
                    'getPrevPageUrl' => null,
                    'getNextPageUrl' => null,
                ],
            ],
            
            // negative number of total items set
            13 => [
                'totalItems' => -10,
                'currentPage' => 1,
                'itemsPerPage' => 200,
                'maxPagesToShow' => 10,
                'expected' => [
                    'pageNames' => [],
                    'getTotalItems' => 0,
                    'getTotalItemsFrom' => 0,
                    'getTotalItemsTo' => 0,
                    'getItemsPerPage' => 200,
                    'getItemsOffset' => 0,
                    'hasPages' => false,
                    'hasCurrentPage' => false,
                    'getCurrentPage' => 1,
                    'getPrevPageUrl' => null,
                    'getNextPageUrl' => null,
                ],
            ],
            
            // negative number of current page set, should not adjust
            // as to check hasCurrentPage()
            14 => [
                'totalItems' => 100,
                'currentPage' => -5,
                'itemsPerPage' => 200,
                'maxPagesToShow' => 10,
                'expected' => [
                    'pageNames' => [],
                    'getTotalItems' => 100,
                    'getTotalItemsFrom' => 1,
                    'getTotalItemsTo' => 100,
                    'getItemsPerPage' => 200,
                    'getItemsOffset' => 0,
                    'hasPages' => false,
                    'hasCurrentPage' => false,
                    'getCurrentPage' => -5,
                    'getPrevPageUrl' => null,
                    'getNextPageUrl' => null,
                ],
            ],
            
            // test max pages to show 3
            15 => [
                'totalItems' => 20,
                'currentPage' => 5,
                'itemsPerPage' => 1,
                'maxPagesToShow' => 3,
                'expected' => [
                    'pageNames' => ['1', '...', '5', '...', '20'],
                    'getTotalItems' => 20,
                    'getTotalItemsFrom' => 5,
                    'getTotalItemsTo' => 5,
                    'getItemsPerPage' => 1,
                    'getItemsOffset' => 4,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 5,
                    'getPrevPageUrl' => 'page/4',
                    'getNextPageUrl' => 'page/6',
                ],
            ],
            
            // test max pages to show lower is lower 3, falls back to 3.
            16 => [
                'totalItems' => 20,
                'currentPage' => 5,
                'itemsPerPage' => 1,
                'maxPagesToShow' => 1,
                'expected' => [
                    'pageNames' => ['1', '...', '5', '...', '20'],
                    'getTotalItems' => 20,
                    'getTotalItemsFrom' => 5,
                    'getTotalItemsTo' => 5,
                    'getItemsPerPage' => 1,
                    'getItemsOffset' => 4,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 5,
                    'getPrevPageUrl' => 'page/4',
                    'getNextPageUrl' => 'page/6',
                ],
            ],
            
            // test current page exceeds total items, total items from and to should be 0.
            17 => [
                'totalItems' => 100,
                'currentPage' => 11,
                'itemsPerPage' => 10,
                'maxPagesToShow' => 3,
                'expected' => [
                    'pageNames' => ['1', '...', '9', '10'],
                    'getTotalItems' => 100,
                    'getTotalItemsFrom' => 0,
                    'getTotalItemsTo' => 0,
                    'getItemsPerPage' => 10,
                    'getItemsOffset' => 100,
                    'hasPages' => true,
                    'hasCurrentPage' => false,
                    'getCurrentPage' => 11,
                    'getPrevPageUrl' => 'page/10',
                    'getNextPageUrl' => null,
                ],
            ],
            
            18 => [
                'totalItems' => 95,
                'currentPage' => 10,
                'itemsPerPage' => 10,
                'maxPagesToShow' => 3,
                'expected' => [
                    'pageNames' => ['1', '...', '10'],
                    'getTotalItems' => 95,
                    'getTotalItemsFrom' => 91,
                    'getTotalItemsTo' => 95,
                    'getItemsPerPage' => 10,
                    'getItemsOffset' => 90,
                    'hasPages' => true,
                    'hasCurrentPage' => true,
                    'getCurrentPage' => 10,
                    'getPrevPageUrl' => 'page/9',
                    'getNextPageUrl' => null,
                ],
            ],            
        ];
    }    
}