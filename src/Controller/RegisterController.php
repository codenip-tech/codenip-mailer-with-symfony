<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mailer\TwigTemplate;
use App\Messenger\Message\UserRegisteredMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterController
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function __invoke(Request $request): Response
    {
        $payload = [
            'name' => $request->query->get('name'),
            'email' => $request->query->get('email'),
        ];

        $this->bus->dispatch(new UserRegisteredMessage($payload['name'], $payload['email']));

        return new JsonResponse();
    }
}