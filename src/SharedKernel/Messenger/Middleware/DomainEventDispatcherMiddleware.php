<?php

declare(strict_types=1);

namespace App\SharedKernel\Messenger\Middleware;

use App\Domain\Event\EmailConfirmed;
use App\Domain\Event\UserWasCreated;
use App\SharedKernel\Event\DomainEvents;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class DomainEventDispatcherMiddleware implements MiddlewareInterface
{
    private const MESSAGE_ROUTING_KEY_MAPPER = [
        UserWasCreated::class => 'user_was_created',
        EmailConfirmed::class => 'user_email_was_confirmed',
    ];

    public function __construct(private readonly MessageBusInterface $eventBus){}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        $domainEvents = DomainEvents::getEvents();
        DomainEvents::clear();

        foreach ($domainEvents as $event) {
            $this->eventBus->dispatch($event, [new AmqpStamp(self::MESSAGE_ROUTING_KEY_MAPPER[$event::class])]);
        }

        return $envelope;
    }
}
