<?php

namespace Domain\ItemList;

use Domain\Common\EventInterface;
use Ramsey\Uuid\UuidInterface;

class ListCreatedEvent implements EventInterface
{
    private UuidInterface $uuid;
    private string $name;

    public function __construct(ItemList $list)
    {
        $this->uuid = $list->getUuid();
        $this->name = $list->getName();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
