<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\License;

use App\ClientContext\Domain\Entity\Client;
use App\LicenseContext\Domain\Entity\License;

final class LicenseProvisioningRequest
{
    /**
     * @param AddonProvision[]  $addons
     * @param DeviceProvision[] $devices
     */
    public function __construct(
        public readonly Client $client,
        public readonly ?License $license,
        public readonly string $period,
        public readonly ?string $specialCode,
        public readonly string $orderType,
        public readonly array $addons,
        public readonly array $devices,
    ) {
    }
}
