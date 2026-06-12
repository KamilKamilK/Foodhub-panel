<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Entity;

use App\Shared\Domain\Enum\CurrencyEnum;
use App\LicenseContext\Domain\Enum\PeriodEnum;
use App\Shared\Domain\ValueObject\Decimal;
use Gedmo\Timestampable\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class License
{
    use Timestampable;

    private ?int $id;
    private ?Decimal $priceMonth;
    private ?Decimal $priceYear;
    private ?string $currency;
    private bool $isVisible;
    private bool $isActive;
    private bool $isTrial;
    private ?int $includedPoses;
    private ?int $menuLimit;
    private int $position;
    private bool $includedFoodHubOrder;
    private Collection $translations;
    private Collection $bonuses;
    private Collection $addons;
    private Collection $additionalDevices;
    private Collection $clientLicenses;

    public function __construct()
    {
        $this->isVisible = true;
        $this->isActive = true;
        $this->isTrial = false;
        $this->includedPoses = 1;
        $this->currency = CurrencyEnum::PLN->value;
        $this->translations = new ArrayCollection();
        $this->bonuses = new ArrayCollection();
        $this->addons = new ArrayCollection();
        $this->additionalDevices = new ArrayCollection();
        $this->clientLicenses = new ArrayCollection();
        $this->includedFoodHubOrder = false;
    }

    public static function create(?Decimal $priceMonth, ?Decimal $priceYear, ?string $currency): self
    {
        $l = new self();
        $l->priceMonth = $priceMonth;
        $l->priceYear  = $priceYear;
        $l->currency   = $currency;
        return $l;
    }

    public function getId(): ?int        { return $this->id; }
    public function getPriceMonth(): ?Decimal { return $this->priceMonth; }
    public function getPriceYear(): ?Decimal  { return $this->priceYear; }
    public function getCurrency(): ?string    { return $this->currency; }

    public function isVisible(): bool { return (bool) $this->isVisible; }
    public function isActive(): bool  { return (bool) $this->isActive; }
    public function isTrial(): bool   { return (bool) $this->isTrial; }

    public function show(): void      { $this->isVisible = true; }
    public function hide(): void      { $this->isVisible = false; }
    public function activate(): void  { $this->isActive = true; }
    public function deactivate(): void { $this->isActive = false; }
    public function markAsTrial(): void    { $this->isTrial = true; }
    public function unmarkAsTrial(): void  { $this->isTrial = false; }

    public function getIncludedPoses(): ?int { return $this->includedPoses; }
    public function getMenuLimit(): ?int     { return $this->menuLimit; }
    public function getPosition(): int       { return $this->position; }

    public function configureCapacity(?int $poses, ?int $menuLimit, int $position): void
    {
        $this->includedPoses = $poses;
        $this->menuLimit     = $menuLimit;
        $this->position      = $position;
    }

    public function getIncludedFoodHubOrder(): bool { return $this->includedFoodHubOrder; }

    public function enableFoodHubOrder(): void  { $this->includedFoodHubOrder = true; }
    public function disableFoodHubOrder(): void { $this->includedFoodHubOrder = false; }

    public function getTranslation(string $locale): ?LicenseTranslation
    {
        return $this->translations->filter(
            fn(LicenseTranslation $t) => $t->getLocale() === $locale
        )->first() ?? null;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(LicenseTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setParent($this);
        }

        return $this;
    }

    public function removeTranslation(LicenseTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
        }

        return $this;
    }

    public function getBonuses(): Collection
    {
        return $this->bonuses;
    }

    public function addBonus(LicenseBonus $bonus): self
    {
        if (!$this->bonuses->contains($bonus)) {
            $this->bonuses->add($bonus);
            $bonus->setLicense($this);
        }

        return $this;
    }

    public function removeBonus(LicenseBonus $bonus): self
    {
        if ($this->bonuses->contains($bonus)) {
            $this->bonuses->removeElement($bonus);
        }

        return $this;
    }

    public function getAddons(): Collection
    {
        return $this->addons;
    }

    public function addAddon(LicenseAddon $addon): self
    {
        if (!$this->addons->contains($addon)) {
            $this->addons->add($addon);
            $addon->setLicense($this);
        }

        return $this;
    }

    public function removeAddon(LicenseAddon $addon): self
    {
        if ($this->addons->contains($addon)) {
            $this->addons->removeElement($addon);
        }

        return $this;
    }

    public function getAdditionalDevices(): Collection
    {
        return $this->additionalDevices;
    }

    public function addAdditionalDevice(LicenseAdditionalDevice $additionalDevice): self
    {
        if (!$this->additionalDevices->contains($additionalDevice)) {
            $this->additionalDevices->add($additionalDevice);
            $additionalDevice->setLicense($this);
        }

        return $this;
    }

    public function removeAdditionalDevice(LicenseAdditionalDevice $additionalDevice): self
    {
        if ($this->additionalDevices->contains($additionalDevice)) {
            $this->additionalDevices->removeElement($additionalDevice);
        }

        return $this;
    }

    public function getClientLicenses(): Collection
    {
        return $this->clientLicenses;
    }

    public function getPriceByPeriod(string $period): ?Decimal
    {
        if ($period === PeriodEnum::MONTH->value) {
            return $this->getPriceMonth();
        } elseif ($period === PeriodEnum::YEAR->value) {
            return $this->getPriceYear();
        }

        return null;
    }
}
