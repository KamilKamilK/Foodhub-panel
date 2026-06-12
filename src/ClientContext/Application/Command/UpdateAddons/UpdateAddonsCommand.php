<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\UpdateAddons;

use App\Shared\Domain\ValueObject\Subdomain;

final class UpdateAddonsCommand
{
    /**
     * @param AddonUpdate[]  $addonUpdates
     * @param DeviceUpdate[] $deviceUpdates
     */
    public function __construct(
        private readonly Subdomain $subdomain,
        private readonly array $addonUpdates,
        private readonly array $deviceUpdates,
    ) {
    }

    public function getSubdomain(): Subdomain { return $this->subdomain; }
    /** @return AddonUpdate[] */
    public function getAddonUpdates(): array  { return $this->addonUpdates; }
    /** @return DeviceUpdate[] */
    public function getDeviceUpdates(): array { return $this->deviceUpdates; }
}
