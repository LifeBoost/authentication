<?php

declare(strict_types=1);

namespace App\SharedKernel\Messenger\Middleware;

use App\SharedKernel\Event\DomainEvents;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class DomainEventDispatcherMiddleware implements MiddlewareInterface
{
    public function __construct(private MessageBusInterface $eventBus){}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        $domainEvents = DomainEvents::getEvents();
        DomainEvents::clear();

        foreach ($domainEvents as $event) {
            $this->eventBus->dispatch($event);
        }

        return $envelope;
    }
}
