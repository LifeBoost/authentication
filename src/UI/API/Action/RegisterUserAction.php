<?php

declare(strict_types=1);

namespace App\UI\API\Action;

use Assert\Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class RegisterUserAction extends AbstractAction
{
    public function __construct(private readonly MessageBusInterface $commandBus){}

    public function __invoke(Request $request): Response
    {
        $data = $request->toArray();

        Assert::lazy()
            ->that()
            ->that()
            ->that()
            ->that()
            ->that()
            ->verifyNow();

        return new JsonResponse([
            'id' => '',
        ]);
    }
}
