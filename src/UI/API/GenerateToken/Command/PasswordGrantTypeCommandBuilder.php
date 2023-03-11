<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken\Command;

use App\Application\GenerateToken\GenerateTokenCommand;
use App\Application\GenerateToken\PasswordGrantType\GenerateTokenPasswordGrantTypeCommand;
use App\UI\API\GenerateToken\Validator\GenerateTokenPasswordGrantTypeValidator;

final class PasswordGrantTypeCommandBuilder implements CommandBuilder
{
    public function build(array $requestData): GenerateTokenCommand
    {
        return new GenerateTokenPasswordGrantTypeCommand(
            $requestData[GenerateTokenPasswordGrantTypeValidator::EMAIL],
            $requestData[GenerateTokenPasswordGrantTypeValidator::PASSWORD],
        );
    }
}