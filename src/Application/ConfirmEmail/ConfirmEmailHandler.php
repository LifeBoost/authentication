<?php

declare(strict_types=1);

namespace App\Application\ConfirmEmail;

use App\Domain\ConfirmationToken;
use App\Domain\Exception\EmailAlreadyConfirmedException;
use App\Domain\UserRepository;
use App\SharedKernel\Exception\NotFoundException;
use App\SharedKernel\Messenger\CommandHandlerInterface;

final class ConfirmEmailHandler implements CommandHandlerInterface
{
    public function __construct(private readonly UserRepository $repository) {}

    /**
     * @throws NotFoundException
     * @throws EmailAlreadyConfirmedException
     */
    public function __invoke(ConfirmEmailCommand $command): void
    {
        $user = $this->repository->getByConfirmationToken(new ConfirmationToken($command->confirmationToken));

        $user->confirmEmail();

        $this->repository->save($user);
    }
}
