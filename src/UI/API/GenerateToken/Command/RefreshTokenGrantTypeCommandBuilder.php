<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken\Command;

use App\Application\GenerateToken\GenerateTokenCommand;
use App\Application\GenerateToken\RefreshTokenGrantType\GenerateTokenRefreshTokenGrantTypeCommand;
use App\UI\API\GenerateToken\Validator\GenerateTokenRefreshGrantTypeValidator;

final class RefreshTokenGrantTypeCommandBuilder implements CommandBuilder
{
    public function build(array $requestData): GenerateTokenCommand
    {
        return new GenerateTokenRefreshTokenGrantTypeCommand(
            $requestData[GenerateTokenRefreshGrantTypeValidator::REFRESH_TOKEN],
        );
    }
}