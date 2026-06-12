<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Setup;

use App\ClientContext\Application\Command\Setup\DTO\Device;
use App\ClientContext\Application\Command\Setup\DTO\User;

final class SetupCommand
{
    public function __construct(
        private readonly User $user,
        private readonly ?Device $device,
        private readonly string $type,
        private readonly array $agreementIds,
        private readonly string $language,
        private readonly bool $withProducts = true,
    ) {
    }

    public function getUser(): User          { return $this->user; }
    public function getDevice(): ?Device     { return $this->device; }
    public function getType(): string        { return $this->type; }
    public function getAgreementIds(): array { return $this->agreementIds; }
    public function getLanguage(): string    { return $this->language; }
    public function isWithProducts(): bool   { return $this->withProducts; }

    public function toArray(): array
    {
        return [
            'user'         => $this->user->toArray(),
            'device'       => $this->device?->toArray(),
            'type'         => $this->type,
            'withProducts' => $this->withProducts,
        ];
    }
}
