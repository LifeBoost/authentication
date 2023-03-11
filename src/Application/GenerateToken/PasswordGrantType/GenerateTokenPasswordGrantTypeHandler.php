<?php

declare(strict_types=1);

namespace App\Application\GenerateToken\PasswordGrantType;

use App\Application\GenerateToken\GeneratedToken;
use App\Domain\PasswordManager;
use App\Domain\TokenService;
use App\Domain\UserRepository;
use App\SharedKernel\Exception\NotFoundException;
use App\SharedKernel\Messenger\CommandHandlerInterface;

final class GenerateTokenPasswordGrantTypeHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepository  $repository,
        private readonly PasswordManager $passwordManager,
        private readonly TokenService    $tokenService,
    ){}

    /**
     * @throws NotFoundException
     */
    public function __invoke(GenerateTokenPasswordGrantTypeCommand $command): GeneratedToken
    {
        $user = $this->repository->getByEmail($command->email);

        if (!$this->passwordManager->isValid($command->password, $user->getPassword())) {
            throw new NotFoundException('User with given credentials not found');
        }

        return $this->tokenService->generateNew($user);
    }
}