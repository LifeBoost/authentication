<?php

declare(strict_types=1);

namespace App\UI\API;

use Assert\Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractAction extends AbstractController
{
    private const AUTHENTICATION_HEADER = 'Authorization';

    protected function getAccessTokenFromRequest(Request $request): string
    {
        $header = $request->headers->get(self::AUTHENTICATION_HEADER);

        Assert::lazy()
            ->that($header, 'Authorization')->notEmpty('Authorization header is required')
            ->verifyNow();

        return trim(str_replace('Bearer', '', $header));
    }
}
