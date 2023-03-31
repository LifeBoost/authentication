<?php

declare(strict_types=1);

namespace App\SharedKernel\Messenger;

use JsonException;
use RuntimeException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class JsonMessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        throw new RuntimeException('This serializer created only for encode messages');
    }

    /**
     * @throws JsonException
     */
    public function encode(Envelope $envelope): array
    {
        return [
            'body' => json_encode($envelope->getMessage()->toArray(), JSON_THROW_ON_ERROR),
        ];
    }
}
