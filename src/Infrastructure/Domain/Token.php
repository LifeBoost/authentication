<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

final class Token
{
    public function __construct(
        public readonly string $token,
        public readonly int $expiresIn,
    ) {}
}
