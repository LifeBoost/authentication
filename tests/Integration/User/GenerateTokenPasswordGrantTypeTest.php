<?php

declare(strict_types=1);

namespace App\Tests\Integration\User;

use App\SharedKernel\Exception\NotFoundException;
use App\Tests\Integration\BaseTestCase;
use App\Tests\Integration\Mother\UserMother;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final class GenerateTokenPasswordGrantTypeTest extends BaseTestCase
{
    /**
     * @test
     * @throws JsonException
     */
    public function generateTokenWithExistedUser(): void
    {
        $this->userMother->create('test2@gmail.com', 'root12345678');
        $this->userMother->confirmEmail('test2@gmail.com');

        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'email' => 'test2@gmail.com',
            'password' => 'root12345678',
            'grantType' => 'password',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayHasKey('accessToken', $responseData);
        self::assertArrayHasKey('refreshToken', $responseData);
        self::assertArrayHasKey('expiresIn', $responseData);
        self::assertArrayHasKey('refreshExpiresIn', $responseData);
        self::assertArrayHasKey('tokenType', $responseData);
        self::assertEquals('jwt', $responseData['tokenType']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function notGenerateTokenWithInvalidPassword(): void
    {
        $this->userMother->create('test2@gmail.com', 'root12345678');

        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'password',
            'email' => 'test2@gmail.com',
            'password' => 'root1234',
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
    public function notGenerateTokenWithoutCreatedUser(): void
    {
        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'password',
            'email' => 'test2@gmail.com',
            'password' => 'root1234',
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
    public function notGeneratedTokenWithInvalidEmail(): void
    {
        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'password',
            'email' => 'test',
            'password' => 'root1234',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Given value is not valid email', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function notGenerateTokenWithoutEmail(): void
    {
        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'password',
            'password' => 'root1234',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Email is required for this authentication type', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function notGenerateTokenWithoutPassword(): void
    {
        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'password',
            'email' => 'test@gmail.com',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Password is required for this authentication type', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function notGenerateTokenWithUnconfirmedEmail(): void
    {
        $this->userMother->create('test2@gmail.com', 'root12345678');

        $response = $this->post(UserMother::TOKEN_URL_PATTERN, [
            'grantType' => 'password',
            'email' => 'test2@gmail.com',
            'password' => 'root12345678',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals(NotFoundException::USER_NOT_FOUND_MESSAGE, $responseData['errors'][0]['message']);
    }
}
