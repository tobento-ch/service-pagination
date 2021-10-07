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
use Tobento\Service\Pagination\UrlGenerator;
use Tobento\Service\Pagination\UrlGeneratorInterface;

/**
 * UrlGeneratorTest tests
 */
class UrlGeneratorTest extends TestCase
{    
    public function testThatUrlGeneratorImplementsUrlGeneratorInterface()
    {
        $this->assertInstanceOf(
            UrlGeneratorInterface::class,
            new UrlGenerator()
        );     
    }
    
    public function testGenerateMethod()
    {
        $g = new UrlGenerator();
        
        $this->assertSame('page/4', $g->generate(4));
    }
    
    public function testAddPageUrlMethodWithCustomPlaceholder()
    {
        $g = new UrlGenerator();
        
        $g->addPageUrl(url: 'page/{number}', placeholder: '{number}');
        
        $this->assertSame('page/4', $g->generate(4));
    }
    
    public function testAddPageUrlMethodWithSpecificUrls()
    {
        $g = new UrlGenerator();
        
        $g->addPageUrl(url: '#{num}', placeholder: '{num}');
        $g->addPageUrl(url: '1#{num}', placeholder: '{num}', page: 1);
        $g->addPageUrl(url: '5#{num}', placeholder: '{num}', page: 5);
        
        $this->assertSame('1#1', $g->generate(1));
        $this->assertSame('5#5', $g->generate(5));
        $this->assertSame('#10', $g->generate(10));
    }
    
    public function testAddPageUrlMethodWithoutPlaceholder()
    {
        $g = new UrlGenerator();
        
        $g->addPageUrl(url: 'page', placeholder: null, page: 1);
        
        $this->assertSame('page', $g->generate(1));
    }    
}