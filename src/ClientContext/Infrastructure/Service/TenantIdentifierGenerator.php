<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Service;

use App\ClientContext\Application\Service\TenantIdentifierGeneratorInterface;

class TenantIdentifierGenerator implements TenantIdentifierGeneratorInterface
{
    private const IDENTIFIER_LENGTH = 10;
    private const ALPHABET = 'abcdefghijklmnopqrstuvwxyz';

    public function generateIdentifier(): string
    {
        $result = '';
        $max = strlen(self::ALPHABET) - 1;
        for ($i = 0; $i < self::IDENTIFIER_LENGTH; $i++) {
            $result .= self::ALPHABET[random_int(0, $max)];
        }
        return $result;
    }

    public function generateActivationToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    public function generateUniqueIdentifier(callable $existsCheck): string
    {
        do {
            $identifier = $this->generateIdentifier();
        } while ($existsCheck($identifier));

        return $identifier;
    }
}
