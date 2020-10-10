<?php

namespace Application\Common;

interface Clock
{
    public function currentDateTime(): \DateTimeInterface;
}
