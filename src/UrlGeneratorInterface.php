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
 * UrlGeneratorInterface
 */
interface UrlGeneratorInterface
{
    /**
     * Generate the url for the specified page number.
     *
     * @param int $pageNumber
     * @return string The generated url.
     */
    public function generate(int $pageNumber): string;
}