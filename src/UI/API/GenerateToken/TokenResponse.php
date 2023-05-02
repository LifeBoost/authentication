<?php

declare(strict_types=1);

namespace App\UI\API\GenerateToken;

use App\Application\GenerateToken\GeneratedToken;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TokenResponse
{
    public static function create(GeneratedToken $token): Response
    {
        $response = new JsonResponse([
            'accessToken' => $token->accessToken,
            'refreshToken' => $token->refreshToken,
            'expiresIn' => $token->expiresIn,
            'refreshExpiresIn' => $token->refreshExpiresIn,
            'tokenType' => $token->tokenType,
        ]);

        $response->headers->setCookie(
            Cookie::create('refreshToken')
                ->withValue($token->refreshToken)
                ->withExpires($token->refreshExpiresIn)
        );

        return $response;
    }
}
