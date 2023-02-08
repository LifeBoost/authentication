<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Event\UserWasRegister;
use App\SharedKernel\Entity;

final class User extends Entity
{
    public function __construct(
        private UserId $id,
        private string $email,
        private string $password,
        private string $firstName,
        private string $lastName,
        private Status $status,
        private ?ConfirmationToken $confirmationToken,
    ){}

    public static function register(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
    ): self {
        $confirmationToken = ConfirmationToken::generate();
        $user = new self(
            UserId::generate(),
            $email,
            $password,
            $firstName,
            $lastName,
            Status::EMAIL_VERIFICATION,
            $confirmationToken,
        );

        $user->publishDomainEvent(
            new UserWasRegister(
                $user->getId()->toString(),
                $user->getEmail(),
                $user->firstName,
                $user->lastName,
                $user->getConfirmationToken()->token
            )
        );

        return $user;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getConfirmationToken(): ?ConfirmationToken
    {
        return $this->confirmationToken;
    }
}
