<?php

namespace Application\Common;

use Ramsey\Uuid\UuidInterface;

interface UuidFactory
{
    public function create(): UuidInterface;
}
