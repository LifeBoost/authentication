<?php

declare(strict_types=1);

namespace App\UI\API\Action;

use App\Application\ConfirmEmail\ConfirmEmailCommand;
use Assert\Assert;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class ConfirmEmailAction extends AbstractAction
{
    public function __construct(private readonly MessageBusInterface $commandBus){}

    public function __invoke(string $token, Request $request): Response
    {
        $redirectUrl = $request->get('redirectUrl');

        Assert::lazy()
            ->that($redirectUrl, 'redirectUrl')->notEmpty('Redirect URL is required')->url('Redirect url is invalid')
            ->that($token, 'token')->uuid('Token is invalid')
            ->verifyNow();

        $this->commandBus->dispatch(new ConfirmEmailCommand($token));

        return new RedirectResponse($redirectUrl);
    }
}
