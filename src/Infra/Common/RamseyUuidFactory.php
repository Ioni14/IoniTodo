<?php

namespace Infra\Common;

use Application\Common\UuidFactory;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class RamseyUuidFactory implements UuidFactory
{
    public function create(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
