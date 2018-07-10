<?php

namespace Bkstg\SearchBundle\Controller;

use Bkstg\CoreBundle\Controller\Controller;
use Bkstg\SearchBundle\Manager\SearchManagerInterface;
use FOS\ElasticaBundle\Finder\FinderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function searchAction(
        FinderInterface $finder,
        SearchManagerInterface $search,
        PaginatorInterface $paginator,
        Request $request
    ) {
        // Create a search query and paginate.
        $query = $search->buildQuery($request->query->get('search'));
        $results = $finder->createPaginatorAdapter($query);
        $results = $paginator->paginate($results, $request->query->getInt('page', 1));

        // Return the response.
        return new Response(
            $this->templating->render(
                '@BkstgSearch/Search/search.html.twig',
                ['results' => $results]
            )
        );
    }
}
