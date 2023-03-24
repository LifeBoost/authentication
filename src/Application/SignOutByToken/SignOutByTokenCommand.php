<?php

declare(strict_types=1);

namespace App\Application\SignOutByToken;

final class SignOutByTokenCommand
{
    public function __construct(
        public readonly string $accessToken,
    ){}
}
