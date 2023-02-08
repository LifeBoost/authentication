<?php

declare(strict_types=1);

namespace App\Application\Register;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
    ){}
}
