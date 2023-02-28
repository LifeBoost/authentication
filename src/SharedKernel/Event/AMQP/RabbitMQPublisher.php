<?php

declare(strict_types=1);

namespace App\SharedKernel\Event\AMQP;

use App\SharedKernel\Event\AMQP\Message;
use App\SharedKernel\Event\AMQP\Publisher;
use JsonException;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitMQPublisher implements Publisher
{
    public function __construct(
        private readonly AMQPConnection $connection,
        private readonly AMQPExchange $exchange,
    ){}

    /**
     * @throws JsonException
     */
    public function publish(Message $message): void
    {
        $channel = $this->connection->channel();

        $channel->basic_publish(
            new AMQPMessage(
                json_encode($message->toArray(), JSON_THROW_ON_ERROR),
            ),
            $this->exchange->exchange,
            $this->exchange->routingKey,
        );
    }
}
