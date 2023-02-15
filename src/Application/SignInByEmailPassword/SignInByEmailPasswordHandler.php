<?php

declare(strict_types=1);

namespace App\Application\SignInByEmailPassword;

use App\Domain\PasswordManager;
use App\Domain\SignInTokenService;
use App\Domain\UserRepository;
use App\SharedKernel\Exception\NotFoundException;
use App\SharedKernel\Messenger\CommandHandlerInterface;

final class SignInByEmailPasswordHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly PasswordManager $passwordManager,
        private readonly SignInTokenService $tokenService,
    ){}

    /**
     * @throws NotFoundException
     */
    public function __invoke(SignInByEmailPasswordCommand $command): string
    {
        $user = $this->repository->getByEmail($command->email);

        if (!$this->passwordManager->isValid($command->password, $user->getPassword())) {
            throw new NotFoundException('User with given credentials not found');
        }

        return $this->tokenService->generateToken($user);
    }
}
