<?php

declare(strict_types=1);

namespace App\SharedKernel\Event\AMQP;

interface Message
{
    public function toArray(): array;
}
