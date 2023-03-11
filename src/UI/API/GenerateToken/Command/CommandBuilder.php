<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken\Command;

use App\Application\GenerateToken\GenerateTokenCommand;

interface CommandBuilder
{
    public function build(array $requestData): GenerateTokenCommand;
}
