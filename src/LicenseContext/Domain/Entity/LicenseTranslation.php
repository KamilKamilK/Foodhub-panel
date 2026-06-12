<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;

class LicenseTranslation
{
    use TimestampableEntity;

    private ?int $id;
    private ?string $name;
    private ?string $description;
    private string $locale;
    private License $parent;

    public static function create(string $locale, string $name, ?string $description): self
    {
        $t = new self();
        $t->locale      = $locale;
        $t->name        = $name;
        $t->description = $description;
        return $t;
    }

    public function getId(): ?int           { return $this->id; }
    public function getName(): ?string      { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function getLocale(): string     { return $this->locale; }
    public function getParent(): License    { return $this->parent; }

    /** @internal For Doctrine bidirectional ORM management — do not call from application code. */
    public function setParent(License $parent): self
    {
        $this->parent = $parent;
        return $this;
    }
}
