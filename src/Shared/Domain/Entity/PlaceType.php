<?php declare(strict_types=1);

namespace App\Shared\Domain\Entity;

class PlaceType
{
    private ?int $id;
    private string $label;
    private string $name;
    private ?int $position;
    private string $icon;
    private ?string $locale;

    public static function create(
        string $label,
        string $name,
        string $icon,
        ?string $locale,
        int $position,
    ): self {
        $p = new self();
        $p->label    = $label;
        $p->name     = $name;
        $p->icon     = $icon;
        $p->locale   = $locale;
        $p->position = $position;
        return $p;
    }

    public function getId(): ?int         { return $this->id; }
    public function getLabel(): string    { return $this->label; }
    public function getName(): string     { return $this->name; }
    public function getPosition(): ?int   { return $this->position; }
    public function getIcon(): string     { return $this->icon; }
    public function getLocale(): ?string  { return $this->locale; }
}
