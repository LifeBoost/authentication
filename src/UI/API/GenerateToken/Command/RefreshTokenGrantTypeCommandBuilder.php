<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken\Command;

use App\Application\GenerateToken\GenerateTokenCommand;

final class RefreshTokenGrantTypeCommandBuilder implements CommandBuilder
{
    public function build(array $requestData): GenerateTokenCommand
    {
        // TODO: Implement make() method.
    }
}