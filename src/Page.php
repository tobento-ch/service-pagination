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
 * Page
 */
class Page implements PageInterface
{
    /**
     * Create a new Page.
     *
     * @param string $name
     * @param int $number
     * @param bool $current 
     * @param bool $ellipsis
     * @param null|string $url
     */    
    public function __construct(
        protected string $name,
        protected int $number,
        protected bool $current,
        protected bool $ellipsis,
        protected null|string $url = null,
    ) {}
    
    /**
     * Returns the page name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
    
    /**
     * Returns the page number.
     *
     * @return int
     */
    public function number(): int
    {
        return $this->number;
    }
    
    /**
     * Returns true if it is the current page, otherwise false.
     *
     * @return bool
     */
    public function current(): bool
    {
        return $this->current;
    }
    
    /**
     * Returns true if it is a page ellipsis, otherwise false.
     *
     * @return bool
     */
    public function ellipsis(): bool
    {
        return $this->ellipsis;
    }    

    /**
     * Returns the page url or null if none.
     *
     * @return null|string
     */
    public function url(): null|string
    {
        return $this->url;
    }    
}