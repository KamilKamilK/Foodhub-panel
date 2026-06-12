<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Entity;

class ClientUser
{
    private ?int $id = null;
    private ?string $email = null;
    private ?string $specialCode = null;
    private ?string $confirmationToken = null;
    private ?Client $client = null;
    private bool $active = false;
    private bool $secondMailSent = false;
    private ?string $activationLink = null;
    private ?string $apiAuthToken = null;

    public function getId(): ?int                { return $this->id; }
    public function getEmail(): ?string          { return $this->email; }
    public function getSpecialCode(): ?string    { return $this->specialCode; }
    public function getConfirmationToken(): ?string { return $this->confirmationToken; }
    public function getClient(): ?Client         { return $this->client; }
    public function isActive(): bool             { return $this->active; }
    public function isSecondMailSent(): bool     { return $this->secondMailSent; }
    public function getActivationLink(): ?string { return $this->activationLink; }
    public function getApiAuthToken(): ?string   { return $this->apiAuthToken; }

    public function setSpecialCode(?string $specialCode): self
    {
        $this->specialCode = $specialCode;
        return $this;
    }

    /** @internal For Doctrine bidirectional ORM management only — do not call from application code. */
    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function storeActivationLink(?string $activationLink): self
    {
        $this->activationLink = $activationLink;
        return $this;
    }

    public static function forAdminCreation(string $email, Client $client, bool $active): self
    {
        $user = new self();
        $user->email  = $email;
        $user->client = $client;
        $user->active = $active;
        return $user;
    }

    public static function forRegistration(string $email, ?string $specialCode, string $confirmationToken, Client $client): self
    {
        $user = new self();
        $user->email             = $email;
        $user->specialCode       = $specialCode;
        $user->confirmationToken = $confirmationToken;
        $user->client            = $client;
        return $user;
    }

    public function updateEmail(string $email): void
    {
        $this->email = $email;
    }

    public function storeApiAuthToken(string $token): void
    {
        $this->apiAuthToken = $token;
    }

    public function confirm(): void
    {
        $this->active            = true;
        $this->activationLink    = null;
        $this->confirmationToken = null;
    }

    public function markSecondMailSent(): void
    {
        $this->secondMailSent = true;
    }
}
