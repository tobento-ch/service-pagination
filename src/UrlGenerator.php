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
 * UrlGenerator
 */
class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var array<mixed> The page urls.
     */
    protected array $pageUrls = [];
    
    /**
     * Generate the url for the specified page number.
     *
     * @param int $pageNumber
     * @return string The generated url.
     */        
    public function generate(int $pageNumber): string
    {
        [$url, $placeholder] = $this->getPageUrlForNum($pageNumber);
        
        return is_null($placeholder) ? $url : str_replace($placeholder, (string)$pageNumber, $url);
    }

    /**
     * Add a url for a given page number.
     *
     * @param string The url.
     * @param null|string A placeholder or null if none.
     * @param null|int If a page number ist set, the url is only for that given number.
     * @return static $this
     */    
    public function addPageUrl(string $url, null|string $placeholder = '{num}', null|int $page = null): static
    {
        $page = $page ?: '*';
        
        $this->pageUrls[$page] = [$url, $placeholder];
        return $this;
    }
    
    /**
     * Get the page url for the given number
     *
     * @param int $num The page number.
     * @return array [$url, $placeholder]
     */
    protected function getPageUrlForNum(int $num): array
    {
        if (empty($this->pageUrls)) {
            return ['page/{num}', '{num}'];
        }

        if (isset($this->pageUrls[$num])) {
            return $this->pageUrls[$num];
        }
        
        if (isset($this->pageUrls['*'])) {
            return $this->pageUrls['*'];
        }    
        
        return ['page/{num}', '{num}'];    
    }    
}