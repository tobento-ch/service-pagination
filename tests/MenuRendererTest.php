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
use Tobento\Service\Pagination\MenuRenderer;
use Tobento\Service\Pagination\RendererInterface;
use Tobento\Service\Pagination\Pagination;

/**
 * MenuRendererTest tests
 */
class MenuRendererTest extends TestCase
{    
    public function testThatMenuRendererImplementsRendererInterface()
    {
        $this->assertInstanceOf(
            RendererInterface::class,
            new MenuRenderer()
        );     
    }
    
    public function testMenuRendererConstructorParameters()
    {
        $renderer = new MenuRenderer(previousText: 'prev', nextText: 'next');
        
        $this->assertInstanceOf(
            RendererInterface::class,
            $renderer
        );        
    }
    
    public function testRenderMethod()
    {
        $renderer = new MenuRenderer(previousText: 'prev', nextText: 'next');

        $pagination = new Pagination(
            totalItems: 100,
            currentPage: 1,
            itemsPerPage: 50,
        );
        
        $this->assertSame(
            '<ul class="pagination"><li class="current"><span>1</span></li><li class="page"><a href="page/2">2</a></li></ul>',
            $renderer->render($pagination)
        );
    }
    
    public function testIfPagesLowerThanTwoNoReturnsEmptyString()
    {
        $renderer = new MenuRenderer();

        $pagination = new Pagination(
            totalItems: 10,
            currentPage: 1,
            itemsPerPage: 50,
        );
        
        $this->assertSame(
            '',
            $renderer->render($pagination)
        );        
    }    
}