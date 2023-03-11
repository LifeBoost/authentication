<?php

declare(strict_types=1);

namespace App\Domain;

use App\Application\GenerateToken\GeneratedToken;

interface TokenService
{
    public function generateNew(User $user): GeneratedToken;
}
