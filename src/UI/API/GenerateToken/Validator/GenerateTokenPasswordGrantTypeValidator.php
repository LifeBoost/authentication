<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken\Validator;

use App\UI\API\GenerateToken\GrantType;
use Assert\Assert;

final class GenerateTokenPasswordGrantTypeValidator implements GenerateTokenValidator
{
    public const GRANT_TYPE = 'grantType';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';

    public function validateRequest(array $request): void
    {
        Assert::lazy()
            ->that($request[self::GRANT_TYPE] ?? null)->notEmpty('Grant type is required')->eq(GrantType::PASSWORD->value)
            ->that($request[self::EMAIL] ?? null)->notEmpty('Email is required for this authentication type')->email('Is not valid email')
            ->that($request[self::PASSWORD] ?? null)->notEmpty('Password is required for this authentication type')
            ->verifyNow();
    }
}
