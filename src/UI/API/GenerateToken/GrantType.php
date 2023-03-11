<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken;

enum GrantType: string
{
    case PASSWORD = 'password';
    case REFRESH_TOKEN = 'refreshToken';
}
