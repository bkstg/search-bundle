<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\Controller;

use Bkstg\CoreBundle\Controller\Controller;
use Bkstg\SearchBundle\Manager\SearchManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function searchAction(
        SearchManagerInterface $search,
        PaginatorInterface $paginator,
        Request $request
    ) {
        // Create a search query and get results and aggregrations.
        $query = $search->buildQuery($request->query->get('search'));
        $results = $search->execute($query);
        $aggregations = $search->getAggregations();

        // Paginate the results.
        $results = $paginator->paginate($results, $request->query->getInt('page', 1));

        // Return the response.
        return new Response(
            $this->templating->render(
                '@BkstgSearch/Search/search.html.twig',
                ['results' => $results, 'aggregations' => $aggregations]
            )
        );
    }
}
