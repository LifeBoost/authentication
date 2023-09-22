<?php

declare(strict_types=1);

namespace App\Application\GetUser;

final class GetUserQuery
{
    public function __construct(
        public readonly string $accessToken,
    ) {}
}
