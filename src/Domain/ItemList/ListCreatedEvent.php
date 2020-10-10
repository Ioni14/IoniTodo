<?php

namespace Domain\ItemList;

use Domain\Common\EventInterface;

class ListCreatedEvent implements EventInterface
{
    private ItemListId $id;
    private string $name;

    public function __construct(ItemListId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function createFromList(ItemList $list): self
    {
        return new self(
            $list->getId(),
            $list->getName(),
        );
    }

    public function getId(): ItemListId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
