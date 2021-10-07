<?php
declare(strict_types=1);

error_reporting( -1 );
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';

use Tobento\Service\Pagination\Pagination;
use Tobento\Service\Pagination\UrlGenerator;
use Tobento\Service\Pagination\MenuRenderer;

// create pagination
$pagination = new Pagination(
    totalItems: 200,
    currentPage: 1,
    itemsPerPage: 10,
    maxPagesToShow: 10,
    urlGenerator: (new UrlGenerator())->addPageUrl('#{num}'),
);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pagination Demo</title>
        
        <link href="pagination.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <article>
            <h1>Pagination Demo</h1>
            
            <p>You might resize your browser to see a possible responsive implementation.</p>
            
            <section>
                <h2>Demo 1</h2>
                <nav><?= $pagination ?></nav>
            </section>
            
            <section>
                <h2>Demo 2</h2>
                <p>With current page number 7</p>
                <nav><?= $pagination->withCurrentPage(7) ?></nav>
            </section>
            
            <section>
                <h2>Demo 3</h2>
                <p>With 4 maximal pages to show</p>
                <nav><?= $pagination->withMaxPagesToShow(4) ?></nav>
            </section>
            
            <section>
                <h2>Demo 4</h2>
                <p>With 4 maximal pages to show and current page number 7</p>
                <nav><?= $pagination->withCurrentPage(7)->withMaxPagesToShow(4) ?></nav>
            </section>
            
            <section>
                <h2>Demo 5</h2>
                <p>With another url pattern</p>
                <nav><?= $pagination->withUrlGenerator((new UrlGenerator())->addPageUrl('#page-{num}')) ?></nav>
            </section>           
            
            <section>
                <h2>Demo 6</h2>
                <p>Changing previous and next text</p>
                <nav><?= $pagination->withRenderer(new MenuRenderer('prev', 'next'))->withCurrentPage(7) ?></nav>
            </section>            

        </article>
    </body>
</html>