<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken;

use App\Application\GenerateToken\GeneratedToken;
use App\Application\GenerateToken\PasswordGrantType\GenerateTokenPasswordGrantTypeCommand;
use App\Application\GenerateToken\RefreshTokenGrantType\GenerateTokenRefreshTokenGrantTypeCommand;
use App\UI\API\AbstractAction;
use App\UI\API\GrantType;
use App\UI\API\InvalidRequestException;
use Assert\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class GenerateAccessTokenAction extends AbstractAction
{
    private const GRANT_TYPE = 'grantType';
    private const EMAIL = 'email';
    private const PASSWORD = 'password';
    private const REFRESH_TOKEN = 'refreshToken';

    private const GRANT_TYPE_ERROR_MESSAGE = 'Grant type is invalid';

    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {}

    /**
     * @throws InvalidRequestException
     */
    public function __invoke(Request $request): Response
    {
        $data = array_merge($request->toArray(), $request->cookies->all());
        $grantType = GrantType::tryFrom($data[self::GRANT_TYPE]);

        return match ($grantType) {
            GrantType::PASSWORD => $this->generateTokenByPassword($request),
            GrantType::REFRESH_TOKEN => $this->generateTokenByRefreshToken($request),
            default => throw new InvalidRequestException(self::GRANT_TYPE_ERROR_MESSAGE),
        };
    }

    private function generateTokenByPassword(Request $request): Response
    {
        $requestData = $request->toArray();

        Assert::lazy()
            ->that($requestData[self::GRANT_TYPE] ?? null)->notEmpty('Grant type is required')->eq(GrantType::PASSWORD->value)
            ->that($requestData[self::EMAIL] ?? null)->notEmpty('Email is required for this authentication type')->email('Given value is not valid email')
            ->that($requestData[self::PASSWORD] ?? null)->notEmpty('Password is required for this authentication type')
            ->verifyNow();

        $command = new GenerateTokenPasswordGrantTypeCommand(
            $requestData[self::EMAIL],
            $requestData[self::PASSWORD],
        );

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        /** @var GeneratedToken $token */
        $token = $this->commandBus
            ->dispatch($command)
            ->last(HandledStamp::class)
            ?->getResult();

        return TokenResponse::create($token);
    }

    private function generateTokenByRefreshToken(Request $request): Response
    {
        $requestData = $request->toArray();
        $refreshToken = $request->cookies->get(self::REFRESH_TOKEN);

        Assert::lazy()
            ->that($requestData[self::GRANT_TYPE] ?? null)->notEmpty('Grant type is required')->eq(GrantType::REFRESH_TOKEN->value)
            ->that($refreshToken)->notEmpty('Refresh token is required for this authentication type')
            ->verifyNow();

        $command = new GenerateTokenRefreshTokenGrantTypeCommand(
            $refreshToken,
        );

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        /** @var GeneratedToken $token */
        $token = $this->commandBus
            ->dispatch($command)
            ->last(HandledStamp::class)
            ?->getResult();

        return TokenResponse::create($token);
    }
}
