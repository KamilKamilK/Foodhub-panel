<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Agreement
{
    private ?int $id;
    private ?string $title;
    private ?string $description;
    private ?bool $required;
    private ?string $locale;
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    public static function create(
        ?string $title,
        ?string $description,
        ?bool $required,
        ?string $locale,
    ): self {
        $a = new self();
        $a->title       = $title;
        $a->description = $description;
        $a->required    = $required;
        $a->locale      = $locale;
        return $a;
    }

    public function getId(): ?int             { return $this->id; }
    public function getTitle(): ?string       { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function getRequired(): ?bool      { return $this->required; }
    public function getLocale(): ?string      { return $this->locale; }
    public function getClients(): Collection  { return $this->clients; }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
        }
        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
        }
        return $this;
    }
}
