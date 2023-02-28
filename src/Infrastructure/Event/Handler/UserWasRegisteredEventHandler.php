<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Handler;

use App\Domain\Event\UserWasRegister;
use App\Infrastructure\Event\Message\UserWasRegisteredMessage;
use App\SharedKernel\Event\AMQP\Publisher;
use App\SharedKernel\Messenger\EventHandlerInterface;

final class UserWasRegisteredEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly Publisher $publisher,
    ){}

    public function __invoke(UserWasRegister $event): void
    {
        $message = new UserWasRegisteredMessage($event->id);

        $this->publisher->publish($message);
    }
}
