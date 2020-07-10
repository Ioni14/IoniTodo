<?php

namespace Application\CreateList;

use Application\Repository\ItemListRepositoryInterface;
use Domain\ItemList\ItemList;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateListService
{
    private ItemListRepositoryInterface $itemListRepository;
    private MessageBusInterface $eventBus;

    public function __construct(ItemListRepositoryInterface $itemListRepository, MessageBusInterface $eventBus)
    {
        $this->itemListRepository = $itemListRepository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(CreateList $command): void
    {
        // TODO : Uuid::uuid4() is Infra related (use Random device)
        $list = ItemList::create(Uuid::uuid4(), $command->name);

        $this->itemListRepository->save($list);

        $list->releaseAndDispatchEvents($this->eventBus);
    }
}
