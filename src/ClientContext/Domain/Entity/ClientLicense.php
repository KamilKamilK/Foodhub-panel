<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Entity;

use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Enum\PeriodEnum;
use Gedmo\Timestampable\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ClientLicense
{
    use Timestampable;

    private ?int $id;
    private ?string $specialCode = null;
    private string $period;
    private \DateTime $validFrom;
    private \DateTime $expiredAt;
    private License $license;
    private Client $client;
    private Collection $addons;
    private Collection $additionalDevices;

    public function __construct()
    {
        $this->addons            = new ArrayCollection();
        $this->additionalDevices = new ArrayCollection();
        $this->period            = PeriodEnum::MONTH->value;
    }

    public static function forTrial(
        License $license,
        Client $client,
        int $durationDays,
        ?string $specialCode,
    ): self {
        $validFrom = new \DateTime();
        $expiredAt = (clone $validFrom)->modify("+{$durationDays} days");
        $cl = new self();
        $cl->license     = $license;
        $cl->client      = $client;
        $cl->validFrom   = $validFrom;
        $cl->expiredAt   = (clone $expiredAt)->setTime(23, 59, 59);
        $cl->specialCode = $specialCode;
        $client->addClientLicense($cl);

        foreach ($license->getAddons() as $addon) {
            $cl->addAddon(ClientAddon::forLicense($cl, $addon, $validFrom, $cl->expiredAt));
        }

        return $cl;
    }

    public static function fromProvision(
        License $license,
        Client $client,
        string $period,
        \DateTime $validFrom,
        \DateTime $expiredAt,
        ?string $specialCode,
    ): self {
        $cl = new self();
        $cl->license     = $license;
        $cl->client      = $client;
        $cl->period      = $period;
        $cl->validFrom   = $validFrom;
        $cl->expiredAt   = (clone $expiredAt)->setTime(23, 59, 59);
        $cl->specialCode = $specialCode;
        return $cl;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialCode(): ?string
    {
        return $this->specialCode;
    }

    public function getPeriod(): string { return $this->period; }

    public function getValidFrom(): \DateTime
    {
        return $this->validFrom;
    }

    /** @internal Required by ClientLicenseEditType Symfony Form binding. */
    public function setExpiredAt(\DateTime $expiredAt): self
    {
        $this->expiredAt = (clone $expiredAt)->setTime(23, 59, 59);

        return $this;
    }

    public function getExpiredAt(): \DateTime
    {
        return $this->expiredAt;
    }

    public function getLicense(): License
    {
        return $this->license;
    }

    /** @internal Required by ClientLicenseEditType Symfony Form binding. */
    public function setLicense(License $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function upgradeLicense(License $newLicense): void
    {
        $this->license = $newLicense;
    }

    public function syncAddonsAndDevicesExpiry(): void
    {
        foreach ($this->addons as $addon) {
            $addon->setExpiredAt($this->expiredAt);
        }
        foreach ($this->additionalDevices as $device) {
            $device->setExpiredAt($this->expiredAt);
        }
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    /** @internal For Doctrine bidirectional ORM management — do not call from application code. */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAddons(): Collection
    {
        return $this->addons;
    }

    public function addAddon(ClientAddon $addon): self
    {
        if (!$this->addons->contains($addon)) {
            $this->addons->add($addon);
            $addon->setClientLicense($this);
        }

        return $this;
    }

    public function removeAddon(ClientAddon $addon): self
    {
        if ($this->addons->contains($addon)) {
            $this->addons->removeElement($addon);
        }

        return $this;
    }

    public function isActive(): bool
    {
        return $this->expiredAt >= new \DateTime();
    }

    public function isExpired(): bool
    {
        return !$this->isActive();
    }

    public function isCurrent(): bool
    {
        $now = new \DateTime();
        return $this->validFrom <= $now && $this->expiredAt >= $now;
    }

    /** Returns the date from which the next period should start (day after expiry). */
    public function nextPeriodStartDate(): \DateTime
    {
        return (clone $this->expiredAt)->modify('+1 day')->setTime(0, 0, 0);
    }

    public function isYearly(): bool
    {
        return $this->period === PeriodEnum::YEAR->value;
    }

    public function isMonthly(): bool
    {
        return $this->period === PeriodEnum::MONTH->value;
    }

    public function getRemainingDays(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return (int) (new \DateTime())->diff($this->expiredAt)->days;
    }

    public function getAdditionalDevices(): Collection
    {
        return $this->additionalDevices;
    }

    public function addAdditionalDevice(ClientAdditionalDevice $additionalDevice): self
    {
        if (!$this->additionalDevices->contains($additionalDevice)) {
            $this->additionalDevices->add($additionalDevice);
            $additionalDevice->setClientLicense($this);
        }

        return $this;
    }

    public function removeAdditionalDevice(ClientAdditionalDevice $additionalDevice): self
    {
        if ($this->additionalDevices->contains($additionalDevice)) {
            $this->additionalDevices->removeElement($additionalDevice);
        }

        return $this;
    }
}
