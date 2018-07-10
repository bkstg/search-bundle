<?php

namespace Bkstg\SearchBundle\EventListener;

use Bkstg\NoticeBoardBundle\Entity\Post;
use Bkstg\SearchBundle\Metadata\MetadataManagerInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JMS\Serializer\SerializerInterface;
use Solarium\Client;

class SearchIndexListener
{
    private $metadata_manager;
    private $client;

    public function __construct(
        MetadataManagerInterface $metadata_manager,
        Client $client
    ) {
        $this->metadata_manager = $metadata_manager;
        $this->client = $client;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $post = $args->getObject();
        if (!$post instanceof Post) {
            return;
        }

        // Create a new update query and document for this action.
        $update = $this->client->createUpdate();
        $document = $update->createDocument();

        // Construct the document.
        $document->addField('id', sprintf('%s:%s', get_class($post), $post->getId()));
        $document->addField('body', $post->getBody());
        $document->addField('created', $post->getCreated());
        $document->addField('updated', $post->getUpdated());
        $groups = [];
        foreach($post->getGroups() as $group) {
            $groups[] = sprintf('%s:%s', get_class($group), $group->getId());
        }
        $document->addField('groups', $groups);

        $update->addDocument($document);
        $update->addCommit();

        $result = $this->client->update($update);
        d($this->metadata_manager);
        d($result);
        d($update);
        d($document);
        d($serialized);die;
    }
}
