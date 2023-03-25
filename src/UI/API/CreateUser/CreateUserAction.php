<?php

declare(strict_types=1);

namespace App\UI\API\CreateUser;

use App\Application\CreateUser\CreateUserCommand;
use App\UI\API\AbstractAction;
use Assert\Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreateUserAction extends AbstractAction
{
    private const EMAIL = 'email';
    private const PASSWORD = 'password';
    private const FIRST_NAME = 'firstName';
    private const LAST_NAME = 'lastName';

    public function __construct(private readonly MessageBusInterface $commandBus){}

    public function __invoke(Request $request): Response
    {
        $data = $request->toArray();

        Assert::lazy()
            ->that($data[self::EMAIL] ?? null, self::EMAIL)->notEmpty('Email is required')->email('Given value is not valid email')
            ->that($data[self::PASSWORD] ?? null, self::PASSWORD)->notEmpty('Password is required')->minLength(8, 'Password length must be at least 8 characters')
            ->that($data[self::FIRST_NAME] ?? null, self::FIRST_NAME)->notEmpty('First name is required')
            ->that($data[self::LAST_NAME] ?? null, self::LAST_NAME)->notEmpty('Last name is required')
            ->verifyNow();

        $command = new CreateUserCommand(
            $data[self::EMAIL],
            $data[self::PASSWORD],
            $data[self::FIRST_NAME],
            $data[self::LAST_NAME],
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(status: Response::HTTP_CREATED);
    }
}
