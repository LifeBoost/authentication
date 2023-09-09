<?php

declare(strict_types=1);

namespace App\Application\GenerateToken\PasswordGrantType;

use App\Application\GenerateToken\GenerateTokenCommand;

final class GenerateTokenPasswordGrantTypeCommand implements GenerateTokenCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
