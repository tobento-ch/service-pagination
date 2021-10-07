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
 * PageInterface
 */
interface PageInterface
{
    /**
     * Returns the page name.
     *
     * @return string
     */
    public function name(): string;
    
    /**
     * Returns the page number.
     *
     * @return int
     */
    public function number(): int;
    
    /**
     * Returns true if it is the current page, otherwise false.
     *
     * @return bool
     */
    public function current(): bool;
    
    /**
     * Returns true if it is a page ellipsis, otherwise false.
     *
     * @return bool
     */
    public function ellipsis(): bool;   

    /**
     * Returns the page url or null if none.
     *
     * @return null|string
     */
    public function url(): null|string;
}