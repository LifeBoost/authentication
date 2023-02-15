<?php

declare(strict_types=1);

namespace App\Domain;

interface SignInTokenService
{
    public function generateToken(User $user): string;
}
