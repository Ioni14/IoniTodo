<?php

namespace Application\CreateList;

class CreateList
{
    public ?string $name;

    public function __construct(?string $name)
    {
        $this->name = $name;
    }
}
