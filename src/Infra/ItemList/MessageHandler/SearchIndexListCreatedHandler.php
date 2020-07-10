<?php

namespace Infra\ItemList\MessageHandler;

use Algolia\AlgoliaSearch\SearchClient;
use Domain\ItemList\ListCreatedEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SearchIndexListCreatedHandler implements MessageHandlerInterface
{
    private SearchClient $searchClient;

    public function __construct(SearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    public function __invoke(ListCreatedEvent $event): void
    {
        $index = $this->searchClient->initIndex('item_list');

        // TODO : add a field "version" to prevent lost-updates #pessimisticLock
        $response = $index->saveObject([
            'objectID' => $event->getUuid(),
            'uuid' => $event->getUuid(),
            'name' => $event->getName(),
        ]);

        if (!$response->valid()) {
            throw new \RuntimeException(sprintf('An error has occurred when saving the ItemList %s.', $event->getUuid()));
        }
    }
}
