<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Entity;

use App\Shared\Domain\Enum\CurrencyEnum;
use App\LicenseContext\Domain\Enum\PeriodEnum;
use App\Shared\Domain\ValueObject\Decimal;
use Gedmo\Timestampable\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class LicenseAddon
{
    use Timestampable;

    private ?int $id;
    private ?Decimal $priceMonth;
    private ?Decimal $priceYear;
    private string $currency;
    private string $type;
    private string $category;
    private Collection $translations;
    private License $license;
    private Collection $clientAddons;

    public function __construct()
    {
        $this->currency = CurrencyEnum::PLN->value;
        $this->translations = new ArrayCollection();
        $this->clientAddons = new ArrayCollection();
    }

    public static function create(
        string $type,
        string $category,
        ?Decimal $priceMonth,
        ?Decimal $priceYear,
        string $currency,
    ): self {
        $a = new self();
        $a->type       = $type;
        $a->category   = $category;
        $a->priceMonth = $priceMonth;
        $a->priceYear  = $priceYear;
        $a->currency   = $currency;
        return $a;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceMonth(): ?Decimal { return $this->priceMonth; }
    public function getPriceYear(): ?Decimal  { return $this->priceYear; }
    public function getCurrency(): string     { return $this->currency; }
    public function getType(): string         { return $this->type; }
    public function getCategory(): string     { return $this->category; }

    public function getTranslation(string $locale): ?LicenseAddonTranslation
    {
        return $this->translations->filter(
            fn(LicenseAddonTranslation $t) => $t->getLocale() === $locale
        )->first() ?? null;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(LicenseAddonTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setParent($this);
        }

        return $this;
    }

    public function removeTranslation(LicenseAddonTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
        }

        return $this;
    }

    public function getLicense(): License
    {
        return $this->license;
    }

    /** @internal For Doctrine bidirectional ORM management — do not call from application code. */
    public function setLicense(License $license): self
    {
        $this->license = $license;
        return $this;
    }

    public function getClientAddons(): Collection
    {
        return $this->clientAddons;
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
