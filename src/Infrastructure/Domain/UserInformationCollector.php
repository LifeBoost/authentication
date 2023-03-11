<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain;

interface UserInformationCollector
{
    public function getIp(): ?string;

    public function getUserAgent(): ?string;
}
