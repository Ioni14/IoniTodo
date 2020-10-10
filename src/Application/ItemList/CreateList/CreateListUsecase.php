<?php

namespace Application\ItemList\CreateList;

use Application\Common\UuidFactory;
use Application\ItemList\Repository\ItemListRepositoryInterface;
use Domain\ItemList\ItemList;
use Domain\ItemList\ItemListId;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateListUsecase
{
    private ItemListRepositoryInterface $itemListRepository;
    private MessageBusInterface $eventBus;
    private UuidFactory $uuidFactory;

    public function __construct(ItemListRepositoryInterface $itemListRepository, MessageBusInterface $eventBus, UuidFactory $uuidFactory)
    {
        $this->itemListRepository = $itemListRepository;
        $this->eventBus = $eventBus;
        $this->uuidFactory = $uuidFactory;
    }

    public function __invoke(CreateList $command): void
    {
        $list = ItemList::create(
            ItemListId::fromString($this->uuidFactory->create()),
            $command->name,
            new \DateTimeImmutable('now')
        );

        $this->itemListRepository->save($list);

        $list->releaseAndDispatchEvents($this->eventBus);
    }
}
