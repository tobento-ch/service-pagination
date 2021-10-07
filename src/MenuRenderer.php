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

namespace Tobento\Service\Pagination;

use Tobento\Service\Menu\Menu;
use Tobento\Service\Menu\MenuInterface;

/**
 * MenuRenderer
 */
class MenuRenderer implements RendererInterface
{
    /**
     * Create a new MenuRenderer.
     *
     * @param string $previousText
     * @param string $nextText
     */    
    public function __construct(
        protected string $previousText = 'Previous',
        protected string $nextText = 'Next',
    ) {}
    
    /**
     * Render the pagination.
     *
     * @return string
     */
    public function render(PaginationInterface $pagination): string
    {
        if ($pagination->getTotalPages() <= 1) {
            return '';    
        }
        
        $menu = new Menu('pagination');
        
        if ($pagination->getPrevPageUrl()) {
            $menu->link($pagination->getPrevPageUrl(), $this->previousText)->id('previous');
        }
            
        $menu->many($pagination->getPages(), function(MenuInterface $menu, Page $page): void {
            
            if ($page->url() && ! $page->current()) {
                $item = $menu->link($page->url(), $page->name())->id($page->number());
            } else {
                $item = $menu->item($page->name())->id($page->number());
                $item->itemTag()->prepend('<span>');
                $item->itemTag()->append('</span>');
            }
            
            if ($page->current()) {
                $item->active();
                $item->itemTag()->class('current');
            }
            
            if ($page->ellipsis() || ! $page->current()) {
                $item->itemTag()->class('page');
            }
        });
        
        if ($pagination->getNextPageUrl()) {
            $menu->link($pagination->getNextPageUrl(), $this->nextText)->id('next');
        }
        
        $menu->tag('ul')->level(0)->class('pagination');
        
        return $menu->render();
    }
}