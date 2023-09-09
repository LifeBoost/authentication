<?php

declare(strict_types=1);

namespace App\Application\InvalidToken;

final class InvalidTokenCommand
{
    public function __construct(
        public readonly string $accessToken,
    ) {
    }
}
