<?php

declare(strict_types=1);

namespace App\UI\API\Action;

use App\Application\SignInByEmailPassword\SignInByEmailPasswordCommand;
use App\Application\SignInCommand;
use Assert\Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SignInByEmailPasswordAction extends AbstractAction
{
    private const EMAIL = 'email';
    private const PASSWORD = 'password';
    private const GRANT_TYPE = 'grantType';
    private const GRANT_TYPE_VALID_TYPES = [
        self::GRANT_TYPE_PASSWORD,
    ];
    private const GRANT_TYPE_PASSWORD = 'password';

    public function __construct(private readonly MessageBusInterface $commandBus){}

    public function __invoke(Request $request): Response
    {
        $data = $request->toArray();

        Assert::lazy()
            ->that($data[self::GRANT_TYPE] ?? null)->notEmpty('Grant type is required')->choice(self::GRANT_TYPE_VALID_TYPES, 'Grant type is invalid')
            ->verifyNow();

        $token = $this->commandBus
            ->dispatch($this->buildCommandByGrantType($data))
            ->last(HandledStamp::class)
            ?->getResult();

        return new JsonResponse([
            'token' => $token,
        ]);
    }

    private function buildCommandByGrantType(array $data): SignInCommand
    {
        if ($data[self::GRANT_TYPE] === self::GRANT_TYPE_PASSWORD) {
            Assert::lazy()
                ->that($data[self::EMAIL] ?? null)->notEmpty('Email is required for this authentication type')->email('Is not valid email')
                ->that($data[self::PASSWORD] ?? null)->notEmpty('Password is required for this authentication type')
                ->verifyNow();

            return new SignInByEmailPasswordCommand(
                $data[self::EMAIL],
                $data[self::PASSWORD],
            );
        }
    }
}
