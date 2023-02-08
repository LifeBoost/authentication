<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\PasswordManager;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class SymfonyHashingPasswordManager implements PasswordManager
{
    public function __construct(private readonly PasswordHasherInterface $passwordHasher = new NativePasswordHasher()){}

    public function hash(string $plainPassword): string
    {
        return $this->passwordHasher->hash($plainPassword);
    }

    public function isValid(string $plainPassword, string $hashedPassword): bool
    {
        return $this->passwordHasher->verify($hashedPassword, $plainPassword);
    }
}
