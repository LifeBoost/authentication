<?php

declare(strict_types=1);

namespace App\SharedKernel\Exception;

use Exception;

final class NotFoundException extends Exception
{
    public const USER_NOT_FOUND_MESSAGE = 'User with given credentials not found';

    public static function notFound(): self
    {
        return new self(self::USER_NOT_FOUND_MESSAGE);
    }
}
