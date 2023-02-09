<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\SharedKernel\Exception\DomainException;

final class EmailAlreadyConfirmedException extends DomainException
{
    public static function create(): self
    {
        return new self('Email for given user already confirmed');
    }
}