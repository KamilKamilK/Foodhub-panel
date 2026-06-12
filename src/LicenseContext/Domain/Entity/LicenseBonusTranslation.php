<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class LicenseBonusTranslation
{
    use TimestampableEntity;

    private ?int $id;
    private ?string $name;
    private string $locale;
    private LicenseBonus $parent;

    public static function create(string $locale, string $name): self
    {
        $t = new self();
        $t->locale = $locale;
        $t->name   = $name;
        return $t;
    }

    public function getId(): ?int        { return $this->id; }
    public function getName(): ?string   { return $this->name; }
    public function getLocale(): string  { return $this->locale; }
    public function getParent(): LicenseBonus { return $this->parent; }

    /** @internal For Doctrine bidirectional ORM management — do not call from application code. */
    public function setParent(LicenseBonus $parent): self
    {
        $this->parent = $parent;
        return $this;
    }
}
