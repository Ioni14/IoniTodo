<?php

namespace Domain\ItemList;

use Domain\Common\EventsRecordableTrait;

/**
 * Aggregate Root of an ItemList
 */
class ItemList
{
    use EventsRecordableTrait;

    private ItemListId $id;
    private string $name;
    private \DateTimeInterface $createdAt;

    private function __construct(ItemListId $id, string $name, \DateTimeInterface $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    public static function create(ItemListId $id, string $name, \DateTimeInterface $createdAt): self
    {
        $self = new self($id, $name, $createdAt);
        $self->recordEvent(ListCreatedEvent::createFromList($self));

        return $self;
    }

    public function getId(): ItemListId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
