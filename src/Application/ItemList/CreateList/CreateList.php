<?php

namespace Application\ItemList\CreateList;

class CreateList
{
    public ?string $name;

    public function __construct(?string $name)
    {
        $this->name = $name;
    }
}
