<?php

declare(strict_types=1);

namespace App\UI\API\GetUser;

use App\Application\GetUser\GetUserQuery;
use App\Application\GetUser\UserDTO;
use App\UI\API\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class GetUserAction extends AbstractAction
{
    public function __construct(
        private readonly MessageBusInterface $queryBus,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $query = new GetUserQuery($this->getAccessTokenFromRequest($request));

        /** @var UserDTO $user */
        $user = $this->queryBus->dispatch($query)->last(HandledStamp::class)?->getResult();

        return $this->json([
            'id' => $user->id,
            'email' => $user->email,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
        ]);
    }
}
