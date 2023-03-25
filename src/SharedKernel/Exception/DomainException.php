<?php

declare(strict_types=1);

namespace App\SharedKernel\Exception;

use Exception;

class DomainException extends Exception
{
    public const USER_WITH_GIVEN_EMAIL_ALREADY_EXISTS_MESSAGE = 'User with given email already exists.';

    public static function alreadyExists(): self
    {
        return new self(self::USER_WITH_GIVEN_EMAIL_ALREADY_EXISTS_MESSAGE);
    }
}
