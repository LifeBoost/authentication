<?php

declare(strict_types=1);

namespace App\Application\SignInByEmailPassword;

use App\Application\SignInCommand;

final class SignInByEmailPasswordCommand implements SignInCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ){}
}
