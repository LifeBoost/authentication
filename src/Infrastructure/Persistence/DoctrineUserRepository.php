<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\User;
use App\Domain\UserRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class DoctrineUserRepository implements UserRepository
{
    public function __construct(private readonly Connection $connection){}

    /**
     * @throws Exception
     */
    public function store(User $user): void
    {
        $this->connection
            ->createQueryBuilder()
            ->insert('users')
            ->values([
                'id' => ':id',
                'email' => ':email',
                'password' => ':password',
                'first_name' => ':firstName',
                'last_name' => ':lastName',
                'status' => ':status',
            ])
            ->setParameters([
                'id' => $user->getId()->toString(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'status' => $user->getStatus()->value,
            ])
            ->executeStatement();

        $this->connection
            ->createQueryBuilder()
            ->insert('users_confirmation')
            ->values([
                'users_id' => ':userId',
                'email' => ':email',
                'confirmation_token' => ':confirmationToken',
            ])
            ->setParameters([
                'userId' => $user->getId()->toString(),
                'email' => $user->getEmail(),
                'confirmationToken' => $user->getConfirmationToken()->token,
            ])
            ->executeStatement();
    }

    /**
     * @throws Exception
     */
    public function save(User $user): void
    {
        $this->connection
            ->createQueryBuilder()
            ->update('users')
            ->set('first_name', ':firstName')
            ->set('last_name', ':lastName')
            ->set('updated_at', ':updatedAt')
            ->setParameters([
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'updatedAt' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            ])
            ->executeStatement();
    }

    /**
     * @throws Exception
     */
    public function existsByEmail(string $email): bool
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('1')
            ->from('users')
            ->where('email = :email')
            ->setParameters([
                'email' => $email
            ])
            ->executeQuery()
            ->rowCount() > 0;
    }
}
