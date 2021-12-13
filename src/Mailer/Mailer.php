<?php

declare(strict_types=1);

namespace App\Mailer;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer implements MailerInterface
{
    public const TEMPLATE_SUBJECT_MAP = [
        TwigTemplate::REGISTER_USER => 'Welcome to my App!!',
    ];

    public function __construct(
        private SymfonyMailerInterface $mailer,
        private Environment $engine,
        private LoggerInterface $logger,
        private string $defaultSender
    ) {
    }

    public function send(string $receiver, string $template, array $payload): void
    {
        $email = (new Email())
            ->from($this->defaultSender)
            ->to($receiver)
            ->subject(self::TEMPLATE_SUBJECT_MAP[$template])
            ->html($this->engine->render($template, $payload));

        try {
            $this->mailer->send($email);
            $this->logger->info(sprintf('Email sent to %s', $receiver));
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(sprintf('Error sending email sent to %s. Error message: %s', $receiver, $e->getMessage()));
        }
    }
}