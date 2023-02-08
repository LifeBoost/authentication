<?php

declare(strict_types=1);

namespace App\Application\ConfirmEmail;

final class ConfirmEmailCommand
{
    public function __construct(public readonly string $confirmationToken){}
}
