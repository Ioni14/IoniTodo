<?php

namespace Domain\ItemList;

use Domain\Common\EventsRecordableTrait;
use Ramsey\Uuid\UuidInterface;

/**
 * Aggregate Root of an ItemList
 */
class ItemList
{
    use EventsRecordableTrait;

    private UuidInterface $uuid;
    private string $name;

    private function __construct(UuidInterface $uuid, string $name)
    {
        $this->uuid = $uuid;
        $this->name = $name;
    }

    public static function create(UuidInterface $uuid, string $name): self
    {
        $self = new self($uuid, $name);
        $self->events[] = new ListCreatedEvent($self);

        return $self;
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
