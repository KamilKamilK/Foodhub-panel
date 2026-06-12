<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\License;

use App\LicenseContext\Domain\Entity\LicenseAddon;

final class AddonProvision
{
    public function __construct(
        public readonly LicenseAddon $licenseAddon,
    ) {
    }
}
