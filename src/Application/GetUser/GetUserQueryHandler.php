<?php

declare(strict_types=1);

namespace App\Application\GetUser;

use App\Domain\UserRepository;
use App\SharedKernel\Messenger\QueryHandlerInterface;

final class GetUserQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserRepository $repository,
    ){}

    public function __invoke(GetUserQuery $query): UserDTO
    {
        $user = $this->repository->getByAccessToken($query->accessToken);

        return new UserDTO(
            $user->getId()->toString(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
        );
    }
}
