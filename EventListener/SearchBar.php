<?php

namespace Bkstg\SearchBundle\EventListener;

use Bkstg\SearchBundle\Block\SearchBarBlock;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class SearchBar
{
    public function onBlock(BlockEvent $event)
    {
        $block = new Block();
        $block->setId(uniqid());
        $block->setSettings($event->getSettings());
        $block->setType(SearchBarBlock::class);

        $event->addBlock($block);
    }
}
