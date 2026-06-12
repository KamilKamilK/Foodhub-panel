<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Entity;

use Gedmo\Timestampable\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class LicenseBonus
{
    use Timestampable;

    private ?int $id;
    private Collection $translations;
    private License $license;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranslation(string $locale): ?LicenseBonusTranslation
    {
        return $this->translations->filter(
            fn(LicenseBonusTranslation $t) => $t->getLocale() === $locale
        )->first() ?? null;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(LicenseBonusTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setParent($this);
        }

        return $this;
    }

    public function removeTranslation(LicenseBonusTranslation $translation): self
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
}
