<?php

declare(strict_types=1);

namespace App\SharedKernel\Event;

interface DomainEvent
{
    public function toArray(): array;
}
