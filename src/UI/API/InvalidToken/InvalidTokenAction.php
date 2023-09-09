<?php

declare(strict_types=1);

namespace App\UI\API\InvalidToken;

use App\Application\InvalidToken\InvalidTokenCommand;
use App\UI\API\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class InvalidTokenAction extends AbstractAction
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $command = new InvalidTokenCommand($this->getAccessTokenFromRequest($request));

        $this->messageBus->dispatch($command);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
