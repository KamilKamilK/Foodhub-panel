<?php declare(strict_types=1);

namespace App\ClientContext\Application\Service;

interface TenantIdentifierGeneratorInterface
{
    public function generateIdentifier(): string;

    public function generateActivationToken(): string;

    /** @param callable(string): bool $existsCheck */
    public function generateUniqueIdentifier(callable $existsCheck): string;
}
