<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\SharedKernel\Event\DomainEvent;

final readonly class UserWasRegister implements DomainEvent
{
    public function __construct(
        public string $id,
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $confirmationToken,
    ){}
}
