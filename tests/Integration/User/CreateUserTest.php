<?php

declare(strict_types=1);

namespace App\Tests\Integration\User;

use App\SharedKernel\Exception\DomainException;
use App\Tests\Integration\BaseTestCase;
use App\Tests\Integration\Mother\UserMother;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final class CreateUserTest extends BaseTestCase
{
    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithValidEmailPasswordFirstNameLastName(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'test@gmail.com',
            'password' => 'root1234',
            'firstName' => 'Testing',
            'lastName' => 'Test',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertEmpty($responseData);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithDuplicatedEmail(): void
    {
        $this->userMother->create('duplicated.email@gmail.com');

        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'duplicated.email@gmail.com',
            'password' => 'root1234',
            'firstName' => 'First',
            'lastName' => 'Last',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals(
            DomainException::USER_WITH_GIVEN_EMAIL_ALREADY_EXISTS_MESSAGE,
            $responseData['errors'][0]['message']
        );
    }

    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithInvalidEmail(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'invalid.email',
            'password' => 'root1234',
            'firstName' => 'First',
            'lastName' => 'Last',
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
    public function createUserWithoutEmail(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'password' => 'root1234',
            'firstName' => 'First',
            'lastName' => 'Last',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Email is required', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithoutPassword(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'test@gmail.com',
            'firstName' => 'First',
            'lastName' => 'Last',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Password is required', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithTooShortPassword(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'test@gmail.com',
            'password' => '1234',
            'firstName' => 'First',
            'lastName' => 'Last',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Password length must be at least 8 characters', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithoutFirstName(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'test@gmail.com',
            'password' => 'root1234',
            'lastName' => 'Last',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('First name is required', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithInvalidFirstName(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'test@gmail.com',
            'password' => 'root1234',
            'firstName' => '',
            'lastName' => 'Last',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('First name is required', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithoutLastName(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'test@gmail.com',
            'password' => 'root1234',
            'firstName' => 'First',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Last name is required', $responseData['errors'][0]['message']);
    }

    /**
     * @test
     * @throws JsonException
     */
    public function createUserWithInvalidLastName(): void
    {
        $response = $this->post(UserMother::URL_PATTERN, [
            'email' => 'test@gmail.com',
            'password' => 'root1234',
            'firstName' => 'First',
            'lastName' => '',
        ]);

        $responseData = $this->parseJson($response->getContent());

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertArrayHasKey('errors', $responseData);
        self::assertEquals('Last name is required', $responseData['errors'][0]['message']);
    }
}
