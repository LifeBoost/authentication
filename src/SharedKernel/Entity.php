<?php

declare(strict_types=1);

namespace App\SharedKernel;

use App\SharedKernel\Event\DomainEvent;
use App\SharedKernel\Event\DomainEvents;

abstract class Entity
{
    final protected function publishDomainEvent(DomainEvent $domainEvent): void
    {
        DomainEvents::publishEvent($domainEvent);
    }
}
