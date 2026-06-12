<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Entity;

use App\Shared\Domain\ValueObject\Decimal;
use Gedmo\Timestampable\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class LicenseSet
{
    use Timestampable;

    private ?int $id;
    private int $position;
    private ?Decimal $price;
    private ?string $currency;
    private Collection $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public static function create(?Decimal $price, ?string $currency, int $position): self
    {
        $s = new self();
        $s->price    = $price;
        $s->currency = $currency;
        $s->position = $position;
        return $s;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): int  { return $this->position; }
    public function getPrice(): ?Decimal { return $this->price; }
    public function getCurrency(): ?string { return $this->currency; }

    public function getTranslation(string $locale): ?LicenseSetTranslation
    {
        return $this->translations->filter(
            fn(LicenseSetTranslation $t) => $t->getLocale() === $locale
        )->first() ?? null;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(LicenseSetTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setParent($this);
        }

        return $this;
    }

    public function removeTranslation(LicenseSetTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
        }

        return $this;
    }
}
