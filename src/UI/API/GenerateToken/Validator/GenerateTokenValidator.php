<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken\Validator;

use App\UI\API\InvalidRequestException;

interface GenerateTokenValidator
{
    /**
     * @throws InvalidRequestException
     */
    public function validateRequest(array $request): void;
}
