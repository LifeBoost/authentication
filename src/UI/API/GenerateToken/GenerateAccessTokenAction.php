<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken;

use App\Application\GenerateToken\GeneratedToken;
use App\UI\API\AbstractAction;
use App\UI\API\GenerateToken\Command\CommandBuilder;
use App\UI\API\GenerateToken\Command\PasswordGrantTypeCommandBuilder;
use App\UI\API\GenerateToken\Command\RefreshTokenGrantTypeCommandBuilder;
use App\UI\API\GenerateToken\Validator\GenerateTokenPasswordGrantTypeValidator;
use App\UI\API\GenerateToken\Validator\GenerateTokenRefreshGrantTypeValidator;
use App\UI\API\GenerateToken\Validator\GenerateTokenValidator;
use App\UI\API\InvalidRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class GenerateAccessTokenAction extends AbstractAction
{
    private const GRANT_TYPE = 'grantType';

    private const GRANT_TYPE_ERROR_MESSAGE = 'Grant type is invalid';

    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly GenerateTokenPasswordGrantTypeValidator $generateTokenPasswordGrantTypeValidator,
        private readonly GenerateTokenRefreshGrantTypeValidator $generateTokenRefreshGrantTypeValidator,
        private readonly PasswordGrantTypeCommandBuilder $passwordGrantTypeCommandBuilder,
        private readonly RefreshTokenGrantTypeCommandBuilder $refreshTokenGrantTypeCommandBuilder,
    ){}

    /**
     * @throws InvalidRequestException
     */
    public function __invoke(Request $request): Response
    {
        $data = $request->toArray();
        $grantType = GrantType::tryFrom($data[self::GRANT_TYPE]);

        $this->getValidator($grantType)->validateRequest($data);
        $command = $this->getCommandBuilder($grantType)->build($data);

        /** @var GeneratedToken $token */
        $token = $this->commandBus
            ->dispatch($command)
            ->last(HandledStamp::class)
            ?->getResult();

        return new JsonResponse([
            'accessToken' => $token->accessToken,
            'refreshToken' => $token->refreshToken,
            'expiresIn' => $token->expiresIn,
            'refreshExpiresIn' => $token->refreshExpiresIn,
            'tokenType' => $token->tokenType,
        ]);
    }

    /**
     * @throws InvalidRequestException
     */
    private function getValidator(?GrantType $grantType): GenerateTokenValidator
    {
        return match($grantType) {
            GrantType::PASSWORD => $this->generateTokenPasswordGrantTypeValidator,
            GrantType::REFRESH_TOKEN => $this->generateTokenRefreshGrantTypeValidator,
            default => throw new InvalidRequestException(self::GRANT_TYPE_ERROR_MESSAGE)
        };
    }

    /**
     * @throws InvalidRequestException
     */
    private function getCommandBuilder(?GrantType $grantType): CommandBuilder
    {
        return match($grantType) {
            GrantType::PASSWORD => $this->passwordGrantTypeCommandBuilder,
            GrantType::REFRESH_TOKEN => $this->refreshTokenGrantTypeCommandBuilder,
            default => throw new InvalidRequestException(self::GRANT_TYPE_ERROR_MESSAGE),
        };
    }
}
