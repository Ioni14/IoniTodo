<?php

namespace Infra\ItemList\Repository;

use Application\ItemList\Repository\ItemListRepositoryInterface;
use Domain\ItemList\ItemList;

class ItemListInMemoryRepository implements ItemListRepositoryInterface
{
    private array $lists = [];

    public function save(ItemList $list): void
    {
        $this->lists[(string) $list->getId()] = $list;
    }

    public function getAll(): array
    {
        return $this->lists;
    }
}
