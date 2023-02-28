<?php

declare(strict_types=1);

namespace App\SharedKernel\Event\AMQP;

use PhpAmqpLib\Connection\AMQPConnection;

final class RabbitMQFactory
{
    private const PUBLISHER_KEY = 'publisher';

    public function __construct(
        private readonly array $configurations,
        private readonly AMQPConnection $connection,
    ){}

    public function getPublisher(string $name): Publisher
    {
        if ($publisherConfiguration = $this->configurations[self::PUBLISHER_KEY][$name] ?? null) {
            return new RabbitMQPublisher(
                $this->connection,
                new AMQPExchange(
                    $publisherConfiguration['exchange'],
                    $publisherConfiguration['routing_key'],
                )
            );
        }
    }
}
