<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

use App\Application\GenerateToken\GeneratedToken;
use App\Domain\UserId;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class OAuthTokenRepository
{
    private const DATABASE_DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        private readonly Connection $connection,
        private readonly UserInformationCollector $userInformationCollector,
    ){}

    /**
     * @throws Exception
     */
    public function store(UserId $userId, GeneratedToken $generatedToken): void
    {
        $this->delete($userId);

        $this->connection
            ->createQueryBuilder()
            ->insert('oauth_access_tokens')
            ->values([
                'users_id' => ':userId',
                'access_token' => ':accessToken',
                'expires_at' => ':expiresAt',
                'ip' => ':ip',
                'user_agent' => ':userAgent',
            ])
            ->setParameters([
                'userId' => $userId->toString(),
                'accessToken' => $generatedToken->accessToken,
                'expiresAt' => (new DateTimeImmutable())->setTimestamp($generatedToken->expiresIn)->format(self::DATABASE_DATETIME_FORMAT),
                'ip' => $this->userInformationCollector->getIp(),
                'userAgent' => $this->userInformationCollector->getUserAgent(),
            ])
            ->executeStatement();

        $this->connection
            ->createQueryBuilder()
            ->insert('oauth_refresh_tokens')
            ->values([
                'users_id' => ':userId',
                'refresh_token' => ':refreshToken',
                'expires_at' => ':expiresAt',
                'ip' => ':ip',
                'user_agent' => ':userAgent',
            ])
            ->setParameters([
                'userId' => $userId->toString(),
                'refreshToken' => $generatedToken->refreshToken,
                'expiresAt' => (new DateTimeImmutable())->setTimestamp($generatedToken->refreshExpiresIn)->format(self::DATABASE_DATETIME_FORMAT),
                'ip' => $this->userInformationCollector->getIp(),
                'userAgent' => $this->userInformationCollector->getUserAgent(),
            ])
            ->executeStatement();
    }

    /**
     * @throws Exception
     */
    public function delete(UserId $userId): void
    {
        $this->connection
            ->createQueryBuilder()
            ->delete('oauth_access_tokens')
            ->where('users_id = :userId')
            ->setParameters([
                'userId' => $userId,
            ])
            ->executeStatement();

        $this->connection
            ->createQueryBuilder()
            ->delete('oauth_refresh_tokens')
            ->where('users_id = :userId')
            ->setParameters([
                'userId' => $userId->toString(),
            ])
            ->executeStatement();
    }
}
