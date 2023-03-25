<?php

declare(strict_types=1);

namespace App\Tests\Integration\User;

use App\SharedKernel\Exception\NotFoundException;
use App\Tests\Integration\BaseTestCase;
use App\Tests\Integration\Mother\UserMother;
use Doctrine\DBAL\Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final class GenerateTokenRefreshTokenGrantTypeTest extends BaseTestCase
{
    /**
     * @test
     * @throws Exception
     * @throws JsonException
     */
    public function generateAccessTokenWithAllValidData(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $refreshToken = $this->userMother->generateTokenGrantTypePassword()['refreshToken'];

        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'refreshToken',
            'refreshToken' =>  $refreshToken,
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayHasKey('accessToken', $responseData);
        self::assertArrayHasKey('refreshToken', $responseData);
        self::assertArrayHasKey('expiresIn', $responseData);
        self::assertArrayHasKey('refreshExpiresIn', $responseData);
        self::assertArrayHasKey('tokenType', $responseData);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function notGenerateAccessTokenWithInvalidRefreshToken(): void
    {
        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'refreshToken',
            'refreshToken' => 'testingInvalidToken',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals(NotFoundException::USER_NOT_FOUND_MESSAGE, $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function notGenerateTokenWithoutRefreshToken(): void
    {
        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'refreshToken',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals(
            'Refresh token is required for this authentication type',
            $responseData['errors'][0]['message']
        );
    }

    /**
     * @test
     * @throws JsonException
     * @throws Exception
     */
    public function notGenerateTokenWithExpiredRefreshToken(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $refreshToken = $this->userMother->generateTokenGrantTypePassword()['refreshToken'];

        // wait for refresh token expiration
        sleep(5);

        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'refreshToken',
            'refreshToken' => $refreshToken,
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals(NotFoundException::USER_NOT_FOUND_MESSAGE, $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     * @throws Exception
     */
    public function notGenerateAccessTokenWhenRefreshTokenNotFound(): void
    {
        $this->userMother->create();
        $this->userMother->confirmEmail();
        $refreshToken = $this->userMother->generateTokenGrantTypePassword()['refreshToken'];
        $this->userMother->deleteUser();

        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'refreshToken',
            'refreshToken' => $refreshToken,
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals(NotFoundException::USER_NOT_FOUND_MESSAGE, $responseData['errors'][0]['message']);
    }
}
