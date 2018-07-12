<?php

namespace Bkstg\SearchBundle\Twig;

use Bkstg\SearchBundle\Aggregation\AggregationLinkInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AggregationExtension extends \Twig_Extension
{
    private $request_stack;
    private $url_generator;


    public function __construct(
        RequestStack $request_stack,
        UrlGeneratorInterface $url_generator
    ) {
        $this->request_stack = $request_stack;
        $this->url_generator = $url_generator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('bkstg_search_aggregation_url', [$this, 'generateUrl']),
        ];
    }

    public function generateUrl(AggregationLinkInterface $link)
    {
        $request = $this->request_stack->getCurrentRequest();
        $parameters = array_merge(
            $request->attributes->get('_route_params', []),
            $request->query->all(),
            [urlencode($link->getProcessor()->getName()) => $link->getQuery()]
        );
        return $this->url_generator->generate($request->attributes->get('_route'), $parameters);
    }
}
