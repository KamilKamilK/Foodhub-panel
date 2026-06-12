<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class LicenseSetTranslation
{
    use TimestampableEntity;

    private ?int $id;
    private ?string $name;
    private ?string $description;
    private ?string $btnName;
    private ?string $btnUrl;
    private string $locale;
    private LicenseSet $parent;

    public static function create(
        string $locale,
        ?string $name,
        ?string $description,
        ?string $btnName,
        ?string $btnUrl,
    ): self {
        $t = new self();
        $t->locale      = $locale;
        $t->name        = $name;
        $t->description = $description;
        $t->btnName     = $btnName;
        $t->btnUrl      = $btnUrl;
        return $t;
    }

    public function getId(): ?int          { return $this->id; }
    public function getName(): ?string     { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function getBtnName(): ?string  { return $this->btnName; }
    public function getBtnUrl(): ?string   { return $this->btnUrl; }
    public function getLocale(): string    { return $this->locale; }
    public function getParent(): LicenseSet { return $this->parent; }

    /** @internal For Doctrine bidirectional ORM management — do not call from application code. */
    public function setParent(LicenseSet $parent): self
    {
        $this->parent = $parent;
        return $this;
    }
}
