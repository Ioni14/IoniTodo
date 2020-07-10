<?php

namespace Domain\ItemList;

use Domain\Common\EventInterface;

class ListCreatedEvent implements EventInterface
{
    private string $uuid;
    private string $name;

    public function __construct(string $uuid, string $name)
    {
        $this->uuid = $uuid;
        $this->name = $name;
    }

    public static function createFromList(ItemList $list): self
    {
        return new self(
            $list->getUuid()->toString(),
            $list->getName(),
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
