<?php

declare(strict_types=1);

namespace App\Domain;

interface UserRepository
{
    public function store(User $user): void;

    public function save(User $user): void;

    public function existsByEmail(string $email): bool;
}
