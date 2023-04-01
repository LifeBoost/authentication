<?php

declare(strict_types=1);

namespace App\Tests\Integration\Mother;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;

final class UserMother
{
    public const URL_PATTERN = 'api/v1/auth/user';
    public const CONFIRM_EMAIL_URL_PATTERN = self::URL_PATTERN . '/confirm';
    public const TOKEN_URL_PATTERN = self::URL_PATTERN . '/token';

    public function __construct(
        private readonly KernelBrowser $client,
    ){}

    /**
     * @throws JsonException
     */
    public function create(
        string $email = 'test@gmail.com',
        string $password = 'root1234',
        string $firstName = 'First',
        string $lastName = 'Last',
    ): array {
        $this->client->restart();

        $this->client->jsonRequest(Request::METHOD_POST, self::URL_PATTERN, [
            'email' => $email,
            'password' => $password,
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);

        return json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws Exception
     */
    public function confirmEmail(string $email = 'test@gmail.com'): void
    {
        /** @var Connection $db */
        $db = $this->client->getContainer()->get(Connection::class);

        $token = $db->createQueryBuilder()
            ->select('confirmation_token')
            ->from('users_confirmation')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery()
            ->fetchAssociative()['confirmation_token'];

        $this->client->restart();

        $this->client->jsonRequest(
            Request::METHOD_GET,
            sprintf('%s/%s?redirectUrl=%s', self::CONFIRM_EMAIL_URL_PATTERN, $token, 'http://localhost'),
        );
    }

    /**
     * @throws JsonException
     */
    public function generateTokenGrantTypePassword(
        string $email = 'test@gmail.com',
        string $password = 'root1234',
    ): array {
        $this->client->restart();

        $this->client->jsonRequest(Request::METHOD_POST, self::TOKEN_URL_PATTERN, [
            'email' => $email,
            'password' => $password,
            'grantType' => 'password',
        ]);

        return json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function generateTokenGrantTypeRefreshToken(
        string $refreshToken,
    ): array {
        $this->client->restart();

        $this->client->jsonRequest(Request::METHOD_POST, self::TOKEN_URL_PATTERN, [
            'grantType' => 'refreshToken',
            'refreshToken' => $refreshToken,
        ]);

        return json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws Exception
     */
    public function deleteUser(string $email = 'test@gmail.com'): void
    {
        /** @var Connection $db */
        $db = $this->client->getContainer()->get(Connection::class);

        $db->createQueryBuilder()
            ->delete('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeStatement();
    }
}
