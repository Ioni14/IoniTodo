<?php

namespace Infra\Repository;

use Application\Repository\ItemListRepositoryInterface;
use Domain\ItemList\ItemList;

class ItemListInMemoryRepository implements ItemListRepositoryInterface
{
    private array $lists = [];

    public function save(ItemList $list): void
    {
        $this->lists[$list->getUuid()->toString()] = $list;
    }
}
