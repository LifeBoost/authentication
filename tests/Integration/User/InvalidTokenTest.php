<?php

declare(strict_types=1);

namespace App\Tests\Integration\User;

use App\Tests\Integration\BaseTestCase;
use App\Tests\Integration\Mother\UserMother;
use Doctrine\DBAL\Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidTokenTest extends BaseTestCase
{
    /**
     * @test
     *
     * @throws JsonException
     * @throws Exception
     */
    public function shouldInvalidAccessTokenWithoutAnyErrors(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $accessToken = $this->userMother->generateTokenGrantTypePassword()['accessToken'];
        $userInfo = $this->userMother->getUserInfo($accessToken);

        self::assertArrayHasKey('id', $userInfo);

        $this->delete(UserMother::TOKEN_URL_PATTERN, $this->getServer($accessToken));

        $userInfo = $this->userMother->getUserInfo($accessToken);

        self::assertArrayHasKey('errors', $userInfo);
    }

    /**
     * @test
     *
     * @throws JsonException
     * @throws Exception
     */
    public function shouldInvalidRefreshTokenWithoutAnyError(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $credentials = $this->userMother->generateTokenGrantTypePassword();
        $userInfo = $this->userMother->getUserInfo($credentials['accessToken']);

        self::assertArrayHasKey('id', $userInfo);

        $this->delete(UserMother::TOKEN_URL_PATTERN, $this->getServer($credentials['accessToken']));

        $credentials = $this->userMother->generateTokenGrantTypeRefreshToken($credentials['refreshToken']);

        self::assertArrayHasKey('errors', $credentials);
    }

    /**
     * @test
     *
     * @throws JsonException
     */
    public function shouldReturnValidationErrorWithoutAuthorizationHeader(): void
    {
        $response = $this->delete(UserMother::TOKEN_URL_PATTERN);

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
