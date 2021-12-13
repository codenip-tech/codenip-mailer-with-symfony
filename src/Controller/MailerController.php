<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mailer\MailerInterface;
use App\Mailer\TwigTemplate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailerController
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(Request $request): Response
    {
       $payload = [
           'name' => $request->query->get('name'),
           'email' => $request->query->get('email'),
       ];

        $this->mailer->send($payload['email'], TwigTemplate::REGISTER_USER, $payload);

        return new JsonResponse();
    }
}