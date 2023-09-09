<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\SharedKernel\Event\DomainEvent;

final class EmailConfirmed implements DomainEvent
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
    ) {
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->id,
            'email' => $this->email,
        ];
    }
}
