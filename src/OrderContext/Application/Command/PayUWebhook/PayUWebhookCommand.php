<?php declare(strict_types=1);

namespace App\OrderContext\Application\Command\PayUWebhook;

final class PayUWebhookCommand
{
    public function __construct(
        private readonly string $body,
    ) {
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
