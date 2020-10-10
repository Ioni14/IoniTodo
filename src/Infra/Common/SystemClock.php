<?php

namespace Infra\Common;

use Application\Common\Clock;

class SystemClock implements Clock
{
    public function currentDateTime(): \DateTimeInterface
    {
        return new \DateTimeImmutable('now');
    }
}
