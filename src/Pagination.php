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

/**
 * Pagination
 */
class Pagination implements PaginationInterface
{
    /**
     * @var int The total number of items for the current page.
     */
    protected int $pageTotalItems = 0;
    
    /**
     * @var int
     */
    protected int $itemsOffset = 0;

    /**
     * @var array<int, PageInterface> The pages.
     */
    protected array $pages = [];

    /**
     * Create a new Pagination.
     *
     * @param int $totalItems The total number of items.
     * @param int $currentPage The current page number.
     * @param int $itemsPerPage The number of items to show per page.
     * @param int $maxPagesToShow The maximal pages to show.
     * @param int $maxItemsPerPage The maximal number of items to show per page.
     * @param null|UrlGeneratorInterface $urlGenerator
     * @param null|RendererInterface $renderer
     */    
    public function __construct(
        protected int $totalItems,
        protected int $currentPage = 1,
        protected int $itemsPerPage = 100,
        protected int $maxPagesToShow = 8,
        protected int $maxItemsPerPage = 1000,
        protected null|UrlGeneratorInterface $urlGenerator = null,
        protected null|RendererInterface $renderer = null,
    ) {
        $this->urlGenerator = $urlGenerator ?: new UrlGenerator();
        $this->renderer = $renderer ?: new MenuRenderer();
        $this->calculate();
    }

    /**
     * Returns a new instance with the specified current page number.
     *
     * @param int $currentPage
     * @return static
     */    
    public function withCurrentPage(int $currentPage): static
    {
        $new = clone $this;
        $new->currentPage = $currentPage;
        $new->calculate();
        return $new;
    }
    
    /**
     * Returns a new instance with the specified number of items to show per page.
     *
     * @param int $itemsPerPage
     * @return static
     */    
    public function withItemsPerPage(int $itemsPerPage): static
    {
        $new = clone $this;
        $new->itemsPerPage = $itemsPerPage;
        $new->calculate();
        return $new;
    }
    
    /**
     * Returns a new instance with the specified maximal pages to show.
     *
     * @param int $maxPagesToShow
     * @return static
     */    
    public function withMaxPagesToShow(int $maxPagesToShow): static
    {        
        $new = clone $this;
        $new->maxPagesToShow = $maxPagesToShow;
        $new->calculate();
        return $new;
    }    
    
    /**
     * Returns a new instance with the specified url generator.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @return static
     */    
    public function withUrlGenerator(UrlGeneratorInterface $urlGenerator): static
    {
        $new = clone $this;
        $new->urlGenerator = $urlGenerator;
        $new->calculate();
        return $new;
    }
    
    /**
     * Returns a new instance with the specified renderer.
     *
     * @param RendererInterface $renderer
     * @return static
     */    
    public function withRenderer(RendererInterface $renderer): static
    {
        $new = clone $this;
        $new->renderer = $renderer;
        return $new;
    }    
    
    /**
     * Gets the total number of items.
     *
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
    
    /**
     * Gets the total number of items from.
     *
     * @return int
     */        
    public function getTotalItemsFrom(): int
    {
        if ($this->totalItems <= 0) {
            return 0;
        }
        
        $from = $this->itemsOffset+1;
        
        return $from > $this->totalItems ? 0 : $from;
    }

    /**
     * Gets the total number of items to.
     *
     * @return int
     */
    public function getTotalItemsTo(): int
    {
        if ($this->getTotalItemsFrom() === 0) {
            return 0;
        }
        
        $to = $this->getTotalItemsFrom() + $this->itemsPerPage - 1;
        
        return $to > $this->totalItems ? $this->totalItems : $to;
    }    

    /**
     * Gets the number of items to show per page.
     *
     * @return int
     */    
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Gets the items offset.
     *
     * @return int
     */    
    public function getItemsOffset(): int
    {
        return $this->itemsOffset;
    }
        
    /**
     * Has pages.
     *
     * @return bool
     */    
    public function hasPages(): bool
    {
        return !empty($this->pages);
    }
    
    /**
     * Gets the pages.
     *
     * @return array<int, PageInterface>
     */    
    public function getPages(): array
    {
        return $this->pages;
    }
    
    /**
     * Gets the total pages.
     *
     * @return int
     */    
    public function getTotalPages(): int
    {
        return count($this->getPages());
    }    

    /**
     * If the page exists.
     *
     * @param int $number
     * @return bool
     */    
    public function hasPage(int $number): bool
    {                
        return array_key_exists($number, $this->pages);
    }
    
    /**
     * If the current page exists.
     *
     * @return bool
     */    
    public function hasCurrentPage(): bool
    {                
        return $this->hasPage($this->getCurrentPage());
    }
    
    /**
     * Gets the current page number.
     *
     * @return int
     */    
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }    
        
    /**
     * Gets the previous page url.
     *
     * @return null|string Previous page url or null if not exists.
     */    
    public function getPrevPageUrl(): null|string
    {
        if ($this->currentPage < 2) {
            return null;
        }
        
        return $this->generateUrl($this->currentPage-1);
    }

    /**
     * Gets the next page url.
     *
     * @return null|string Next page url or null if not exists.
     */    
    public function getNextPageUrl(): null|string
    {
        if ($this->currentPage < 1) {
            return null;
        }
        
        if ($this->currentPage+1 >= $this->pageTotalItems) {
            return null;
        }
        
        return $this->generateUrl($this->currentPage+1);
    }
    
    /**
     * Returns the string representation of the pagination.
     *
     * @return string
     */    
    public function render(): string
    {        
        return $this->renderer ? $this->renderer->render($this) : '';
    }
    
    /**
     * Returns the string representation of the pagination.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
    
    /**
     * Calculates the numbers.
     *
     * @return void
     */    
    protected function calculate(): void
    {
        // Total items cannot be less than 0.
        if ($this->totalItems < 0) {
            $this->totalItems = 0;
        }
        
        // Max pages to show cannot be less than 3.
        if ($this->maxPagesToShow < 3) {
            $this->maxPagesToShow = 3;
        }
        
        // Handle items per page.
        if ($this->itemsPerPage < 1) {
            $this->itemsPerPage = 1;
        }
        
        // Handle items maximal number per page.
        if ($this->itemsPerPage > $this->maxItemsPerPage) {
            $this->itemsPerPage = $this->maxItemsPerPage;
        }       
        
        // Calculate the items offset.
        $itemsOffset = ($this->currentPage-1)*$this->itemsPerPage;
        $this->itemsOffset = ($itemsOffset < 0) ? 0 : $itemsOffset;
        
        // Calculate page total items.
        $this->pageTotalItems = (int) ceil($this->totalItems / $this->itemsPerPage);
                
        // Create pages.
        $this->createPages();
    }
    
    /**
     * Creates the pages.
     *
     * @return void
     */
    protected function createPages(): void
    {
        // Reset pages for new instances.
        $this->pages = [];
        
        // If the page has no items, do not create pages at all.
        if ($this->pageTotalItems <= 0) {
            return;
        }
        
        if ($this->currentPage < 1) {
            return;
        }
        
        // Determine the page range, centered around the current page.
        $rangeOffset = (int) floor(($this->maxPagesToShow - 3) / 2);
                
        if ($this->currentPage + $rangeOffset > $this->pageTotalItems) {
            $rangeStart = $this->pageTotalItems - $this->maxPagesToShow + 2;
        } else {
            $rangeStart = $this->currentPage - $rangeOffset;
        }
        
        if ($rangeStart < 2) {
            $rangeStart = 2;
        }
        
        $rangeEnd = $rangeStart + $this->maxPagesToShow - 3;
                    
        if ($rangeEnd >= $this->pageTotalItems) {
            $rangeEnd = $this->pageTotalItems - 1;
        }
        
        // Build the pages.
        $this->createPage(1, 1);
        
        if ($rangeStart > 2) { // create ellipsis ...
            $startNum = floor($rangeStart/2);
            $startNum = ($startNum < 2) ? 2 : $startNum;
            $this->createPage((int)$startNum, 0, null, true);
        }

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $this->createPage($i, $i);
        }
        
        if ($rangeEnd < $this->pageTotalItems - 1) { // create ellipsis ...
            $this->createPage((int)floor(($this->pageTotalItems-$rangeEnd)/2)+$rangeEnd, 0, null, true);
        }
        
        $this->createPage($this->pageTotalItems, $this->pageTotalItems);
    }

    /**
     * Create a page.
     *
     * @param int $num The page number.
     * @param int $current The current number to compare.
     * @param null|string $url The page url.
     * @param bool $isEllipsis If it is a page ellipsis '...'.
     * @return void
     */
    protected function createPage(int $num, int $current, null|string $url = null, bool $isEllipsis = false): void
    {
        if ($url === null) {
            $url = $this->generateUrl($num);
        }
        
        $current = ($this->currentPage === $current) ? true : false;
        $name = ($isEllipsis === true) ? '...' : (string)$num;
        
        $this->pages[$num] = new Page($name, $num, $current, $isEllipsis, $url);
    }

    /**
     * Generate the url for the specified page number.
     *
     * @param int $num The page number.
     * @return string The page url.
     */
    protected function generateUrl(int $num): string
    {
        return $this->urlGenerator ? $this->urlGenerator->generate($num) : '';
    }            
}