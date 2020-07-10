<?php

namespace Application\ItemList\Repository;

use Domain\ItemList\ItemList;

interface ItemListRepositoryInterface
{
    public function save(ItemList $list): void;
}
