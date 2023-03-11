<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Infrastructure\Domain\UserInformationCollector;
use Symfony\Component\HttpFoundation\RequestStack;

final class SymfonyUserInformationCollector implements UserInformationCollector
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ){}

    public function getIp(): ?string
    {
        return $this->requestStack->getCurrentRequest()?->getClientIp();
    }

    public function getUserAgent(): ?string
    {
        return $this->requestStack->getCurrentRequest()->headers->get('User-Agent');
    }
}