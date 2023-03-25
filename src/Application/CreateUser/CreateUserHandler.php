<?php

declare(strict_types=1);

namespace App\Application\CreateUser;

use App\Domain\PasswordManager;
use App\Domain\User;
use App\Domain\UserRepository;
use App\SharedKernel\Exception\DomainException;
use App\SharedKernel\Messenger\CommandHandlerInterface;

final class CreateUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly PasswordManager $passwordManager,
    ){}

    /**
     * @throws DomainException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        if ($this->repository->existsByEmail($command->email)) {
            throw DomainException::alreadyExists();
        }

        $user = User::create(
            $command->email,
            $this->passwordManager->hash($command->password),
            $command->firstName,
            $command->lastName,
        );

        $this->repository->store($user);
    }
}
