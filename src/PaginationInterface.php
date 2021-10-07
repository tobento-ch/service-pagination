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
 * PaginationInterface
 */
interface PaginationInterface
{
    /**
     * Returns a new instance with the specified current page number.
     *
     * @param int $currentPage
     * @return static
     */    
    public function withCurrentPage(int $currentPage): static;
    
    /**
     * Returns a new instance with the specified number of items to show per page.
     *
     * @param int $itemsPerPage
     * @return static
     */    
    public function withItemsPerPage(int $itemsPerPage): static;
    
    /**
     * Returns a new instance with the specified maximal pages to show.
     *
     * @param int $maxPagesToShow
     * @return static
     */    
    public function withMaxPagesToShow(int $maxPagesToShow): static; 
    
    /**
     * Returns a new instance with the specified url generator.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @return static
     */    
    public function withUrlGenerator(UrlGeneratorInterface $urlGenerator): static;
    
    /**
     * Returns a new instance with the specified renderer.
     *
     * @param RendererInterface $renderer
     * @return static
     */    
    public function withRenderer(RendererInterface $renderer): static;
    
    /**
     * Gets the total number of items.
     *
     * @return int
     */
    public function getTotalItems(): int;
    
    /**
     * Gets the total number of items from.
     *
     * @return int
     */        
    public function getTotalItemsFrom(): int;

    /**
     * Gets the total number of items to.
     *
     * @return int
     */
    public function getTotalItemsTo(): int;   

    /**
     * Gets the number of items to show per page.
     *
     * @return int
     */    
    public function getItemsPerPage(): int;

    /**
     * Gets the items offset.
     *
     * @return int
     */    
    public function getItemsOffset(): int;
        
    /**
     * Has pages.
     *
     * @return bool
     */    
    public function hasPages(): bool;
    
    /**
     * Gets the pages.
     *
     * @return array<int, PageInterface>
     */    
    public function getPages(): array;

    /**
     * Gets the total pages.
     *
     * @return int
     */    
    public function getTotalPages(): int;
    
    /**
     * If the page exists.
     *
     * @param int $number
     * @return bool
     */    
    public function hasPage(int $number): bool;
    
    /**
     * If the current page exists.
     *
     * @return bool
     */    
    public function hasCurrentPage(): bool;

    /**
     * Gets the current page number.
     *
     * @return int
     */    
    public function getCurrentPage(): int;
    
    /**
     * Gets the previous page url.
     *
     * @return null|string Previous page url or null if not exists.
     */    
    public function getPrevPageUrl(): null|string;

    /**
     * Gets the next page url.
     *
     * @return null|string Next page url or null if not exists.
     */    
    public function getNextPageUrl(): null|string;
    
    /**
     * Returns the string representation of the pagination.
     *
     * @return string
     */    
    public function render(): string;
}