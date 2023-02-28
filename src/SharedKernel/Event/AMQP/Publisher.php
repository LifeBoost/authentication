<?php

declare(strict_types=1);

namespace App\SharedKernel\Event\AMQP;

interface Publisher
{
    public function publish(Message $message): void;
}
