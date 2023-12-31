<?php

declare(strict_types=1);

namespace App\Application\CreateUser;

final class CreateUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $firstName,
        public readonly string $lastName,
    ) {}
}
