<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Message;

use App\SharedKernel\Event\AMQP\Message;
use DateTimeImmutable;

final class UserWasRegisteredMessage implements Message
{
    public function __construct(
        public readonly string $userId,
        public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable(),
    ){}

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'occurredAt' => $this->occurredAt->getTimestamp(),
        ];
    }
}
