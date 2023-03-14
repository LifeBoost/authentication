<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken\Validator;

use App\UI\API\GenerateToken\GrantType;
use Assert\Assert;

final class GenerateTokenRefreshGrantTypeValidator implements GenerateTokenValidator
{
    public const GRANT_TYPE = 'grantType';
    public const REFRESH_TOKEN = 'refreshToken';

    public function validateRequest(array $request): void
    {
        Assert::lazy()
            ->that($request[self::GRANT_TYPE] ?? null)->notEmpty('Grant type is required')->eq(GrantType::REFRESH_TOKEN->value)
            ->that($request[self::REFRESH_TOKEN] ?? null)->notEmpty('Refresh token is required for this authentication type')->string('Is not valid refresh token')
            ->verifyNow();
    }
}
