<?php

declare(strict_types=1);

namespace App\Domain;

interface PasswordManager
{
    public function hash(string $plainPassword): string;
    public function isValid(string $plainPassword, string $hashedPassword): bool;
}
