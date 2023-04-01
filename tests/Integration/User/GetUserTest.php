<?php

declare(strict_types=1);

namespace App\Tests\Integration\User;

use App\Tests\Integration\BaseTestCase;
use App\Tests\Integration\Mother\UserMother;
use Doctrine\DBAL\Exception;
use JsonException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

final class GetUserTest extends BaseTestCase
{
    /**
     * @test
     *
     * @throws JsonException
     * @throws Exception
     */
    public function foundUserWhenGrantTypeIsPassword(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $accessToken = $this->userMother->generateTokenGrantTypePassword()['accessToken'];

        $response = $this->get(UserMother::URL_PATTERN, server: $this->getServer($accessToken));

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayHasKey('id', $responseData);
        self::assertTrue(Uuid::isValid($responseData['id']));
        self::assertArrayHasKey('email', $responseData);
        self::assertEquals('test@gmail.com', $responseData['email']);
        self::assertArrayHasKey('firstName', $responseData);
        self::assertEquals('First', $responseData['firstName']);
        self::assertArrayHasKey('lastName', $responseData);
        self::assertEquals('Last', $responseData['lastName']);
    }

    /**
     * @test
     *
     * @throws JsonException
     * @throws Exception
     */
    public function foundUserWhenGrantTypeIsRefreshToken(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $refreshToken = $this->userMother->generateTokenGrantTypePassword()['refreshToken'];
        sleep(3);
        $accessToken = $this->userMother->generateTokenGrantTypeRefreshToken($refreshToken)['accessToken'];

        $response = $this->get(UserMother::URL_PATTERN, server: $this->getServer($accessToken));

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayHasKey('id', $responseData);
        self::assertTrue(Uuid::isValid($responseData['id']));
        self::assertArrayHasKey('email', $responseData);
        self::assertEquals('test@gmail.com', $responseData['email']);
        self::assertArrayHasKey('firstName', $responseData);
        self::assertEquals('First', $responseData['firstName']);
        self::assertArrayHasKey('lastName', $responseData);
        self::assertEquals('Last', $responseData['lastName']);
    }


    /**
     * @test
     *
     * @throws JsonException
     * @throws Exception
     */
    public function notFoundUserWhenAccessTokenIsInvalid(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $this->userMother->generateTokenGrantTypePassword();

        $response = $this->get(UserMother::URL_PATTERN, server: $this->getServer('testing.test'));

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws JsonException
     * @throws Exception
     */
    public function notFoundUserWhenAccessTokenIsNotExists(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $refreshToken = $this->userMother->generateTokenGrantTypePassword()['refreshToken'];

        $response = $this->get(UserMother::URL_PATTERN, server: $this->getServer($refreshToken));

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws JsonException
     * @throws Exception
     */
    public function notFoundUserWhenAccessTokenIsExpired(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $accessToken = $this->userMother->generateTokenGrantTypePassword()['accessToken'];
        sleep(3);

        $response = $this->get(UserMother::URL_PATTERN, server: $this->getServer($accessToken));

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     *
     * @throws JsonException
     * @throws Exception
     */
    public function shouldReturnValidationErrorWithoutAuthorizationHeader(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $this->userMother->generateTokenGrantTypePassword();

        $response = $this->get(UserMother::URL_PATTERN);
        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Authorization header is required', $responseData['errors'][0]['message']);
    }

    private function getServer(string $accessToken): array
    {
        return [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $accessToken),
        ];
    }
}
