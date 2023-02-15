<?php

declare(strict_types=1);

namespace App\Domain;

use App\SharedKernel\Exception\NotFoundException;

interface UserRepository
{
    public function store(User $user): void;

    public function save(User $user): void;

    public function existsByEmail(string $email): bool;

    /**
     * @throws NotFoundException
     */
    public function getByConfirmationToken(ConfirmationToken $token): User;

    public function getByEmail(string $email): User;
}
