<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\Aggregation;

use Bkstg\SearchBundle\BkstgSearchBundle;
use Elastica\Aggregation\AbstractAggregation;
use Elastica\Query\AbstractQuery;
use Elastica\QueryBuilder;
use Symfony\Component\Translation\TranslatorInterface;

class TypeAggregation implements AggregationProcessorInterface
{
    private $links = [];
    private $active;

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getName(): string
    {
        return '_type';
    }

    public function getAggregation(): AbstractAggregation
    {
        $qb = new QueryBuilder();

        return $qb
            ->aggregation()
            ->terms($this->getName())
            ->setField('_type')
            ->setMinimumDocumentCount(2);
    }

    public function getQuery($value): AbstractQuery
    {
        $qb = new QueryBuilder();

        return $qb->query()->terms('_type', $value);
    }

    public function getLabel(): string
    {
        return $this->translator->trans('aggregation.label.type', [], BkstgSearchBundle::TRANSLATION_DOMAIN);
    }

    public function buildLinks($aggregation, $value): void
    {
        foreach ($aggregation['buckets'] as $bucket) {
            // Reset the query on each bucket to build queries.
            $query = is_array($value) ? $value : [];

            // Create a new link.
            $link = new AggregationLink($this);
            $link->setLabel($this->translator->trans(
                'aggregation.type.' . $bucket['key'],
                [],
                BkstgSearchBundle::TRANSLATION_DOMAIN
            ));
            $link->setCount($bucket['doc_count']);

            // Link is active if the key is in the query.
            $link->setActive(in_array($bucket['key'], $query));
            if ($link->isActive()) {
                // If this link is active set facet to active.
                $this->active = true;

                // Remove the key from the query.
                unset($query[array_search($bucket['key'], $value)]);
            } else {
                // Add the key to the query.
                $query[] = $bucket['key'];
            }

            // Add the query to the link and push on.
            $link->setQuery($query);
            $this->links[] = $link;
        }
    }

    public function isActive(): bool
    {
        return true === $this->active;
    }

    public function getLinks(): array
    {
        return $this->links;
    }
}
