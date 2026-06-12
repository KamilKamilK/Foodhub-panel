<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Entity;

use App\LicenseContext\Domain\Entity\LicenseAddon;
use Gedmo\Timestampable\Traits\Timestampable;

class ClientAddon
{
    use Timestampable;

    private ?int $id;
    private bool $isActive;
    private \DateTime $validFrom;
    private \DateTime $expiredAt;
    private ClientLicense $clientLicense;
    private LicenseAddon $licenseAddon;

    public function __construct()
    {
        $this->isActive = true;
    }

    public static function forLicense(
        ClientLicense $clientLicense,
        LicenseAddon $licenseAddon,
        \DateTime $validFrom,
        \DateTime $expiredAt,
    ): self {
        $addon = new self();
        $addon->clientLicense = $clientLicense;
        $addon->licenseAddon  = $licenseAddon;
        $addon->validFrom     = $validFrom;
        $addon->expiredAt     = (clone $expiredAt)->setTime(23, 59, 59);
        return $addon;
    }

    public static function forProvision(
        LicenseAddon $licenseAddon,
        \DateTime $validFrom,
        \DateTime $expiredAt,
    ): self {
        $addon = new self();
        $addon->licenseAddon = $licenseAddon;
        $addon->validFrom    = $validFrom;
        $addon->expiredAt    = (clone $expiredAt)->setTime(23, 59, 59);
        return $addon;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isPlannedForNextPeriod(): ?bool
    {
        return $this->isActive;
    }

    public function setPlannedForNextPeriod(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function planForNextPeriod(): void   { $this->isActive = true; }
    public function cancelNextPeriod(): void    { $this->isActive = false; }

    public function getValidFrom(): \DateTime   { return $this->validFrom; }
    public function getExpiredAt(): \DateTime   { return $this->expiredAt; }
    public function getClientLicense(): ClientLicense { return $this->clientLicense; }
    public function getLicenseAddon(): LicenseAddon   { return $this->licenseAddon; }

    /** @internal Called from ClientLicense::syncAddonsAndDevicesExpiry() — do not call from application code. */
    public function setExpiredAt(\DateTime $expiredAt): self
    {
        $this->expiredAt = (clone $expiredAt)->setTime(23, 59, 59);
        return $this;
    }

    /** @internal For Doctrine bidirectional ORM management — do not call from application code. */
    public function setClientLicense(ClientLicense $clientLicense): self
    {
        $this->clientLicense = $clientLicense;
        return $this;
    }

    public function isActive(): bool
    {
        return ($this->isActive ?? false) && $this->expiredAt >= new \DateTime();
    }

    public function isExpired(): bool
    {
        return $this->expiredAt < new \DateTime();
    }

    public function migrateToAddon(LicenseAddon $newAddon): void
    {
        $this->licenseAddon = $newAddon;
    }
}
