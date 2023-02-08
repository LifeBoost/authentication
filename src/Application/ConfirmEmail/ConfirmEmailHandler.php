<?php

declare(strict_types=1);

namespace App\Application\ConfirmEmail;

use App\Domain\UserRepository;
use App\SharedKernel\Messenger\CommandHandlerInterface;

final class ConfirmEmailHandler implements CommandHandlerInterface
{
    public function __construct(private readonly UserRepository $repository){}

    public function __invoke(ConfirmEmailCommand $command): void
    {
    }
}
