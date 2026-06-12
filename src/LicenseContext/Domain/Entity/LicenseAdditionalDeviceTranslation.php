<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class LicenseAdditionalDeviceTranslation
{
    use TimestampableEntity;

    private ?int $id;
    private ?string $name;
    private string $locale;
    private LicenseAdditionalDevice $parent;

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
    public function getParent(): LicenseAdditionalDevice { return $this->parent; }

    /** @internal For Doctrine bidirectional ORM management — do not call from application code. */
    public function setParent(LicenseAdditionalDevice $parent): self
    {
        $this->parent = $parent;
        return $this;
    }
}
