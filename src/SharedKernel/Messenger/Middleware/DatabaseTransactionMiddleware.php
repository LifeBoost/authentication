<?php

declare(strict_types=1);

namespace App\SharedKernel\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class DatabaseTransactionMiddleware implements MiddlewareInterface
{
    public function __construct(){}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        // TODO: Implement handle() method.
    }
}