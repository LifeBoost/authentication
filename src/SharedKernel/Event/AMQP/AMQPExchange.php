<?php

declare(strict_types=1);

namespace App\SharedKernel\Event\AMQP;

final class AMQPExchange
{
    public function __construct(
        public readonly string $exchange,
        public readonly string $routingKey,
    ){}
}
