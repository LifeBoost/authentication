<?php

declare(strict_types=1);

namespace App\Domain;

use Ramsey\Uuid\Uuid;

final readonly class ConfirmationToken
{
    public function __construct(public string $token){}

    public static function generate(): self
    {
        return new self(
            Uuid::uuid4()->toString(),
        );
    }
}
