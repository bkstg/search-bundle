<?php

namespace Bkstg\SearchBundle\Aggregation;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\SearchBundle\BkstgSearchBundle;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Aggregation\AbstractAggregation;
use Elastica\QueryBuilder;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Symfony\Component\Translation\TranslatorInterface;

class ProductionAggregation implements AggregationProcessorInterface
{
    private $links = [];
    private $active;

    private $translator;
    private $em;

    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $em
    ) {
        $this->translator = $translator;
        $this->em = $em;
    }

    public function getName(): string
    {
        return 'production';
    }

    public function getAggregation(): AbstractAggregation
    {
        $qb = new QueryBuilder();
        return $qb
            ->aggregation()
            ->terms($this->getName())
            ->setField('groups.id')
            ->setMinimumDocumentCount(2);
    }

    public function getQuery($value): AbstractQuery
    {
        $qb = new QueryBuilder();
        return $qb->query()->terms('groups.id', $value);
    }

    public function getLabel(): string
    {
        return $this->translator->trans('aggregation.label.production', [], BkstgSearchBundle::TRANSLATION_DOMAIN);
    }

    public function buildLinks($aggregation, $value)
    {
        $repo = $this->em->getRepository(Production::class);
        foreach ($aggregation['buckets'] as $bucket) {
            if (null === $production = $repo->findOneBy(['id' => $bucket['key']])) {
                continue;
            }

            // Reset the query on each bucket to build queries.
            $query = is_array($value) ? $value : [];

            // Create a new link.
            $link = new AggregationLink($this);
            $link->setLabel($production->getName());
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
        return $this->active === true;
    }

    public function getLinks(): array
    {
        return $this->links;
    }
}
