<?php declare(strict_types=1);

namespace App\Shared\Domain\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    private ?int $id;
    private string $email;
    private string $password;
    private ?string $plainPassword;
    private ?string $salt;

    public function __construct()
    {
        $this->setSalt(md5((string) time()));
    }

    public static function create(string $email, ?string $plainPassword): self
    {
        $u = new self();
        $u->email         = $email;
        $u->plainPassword = $plainPassword;
        return $u;
    }

    public function getId(): ?int             { return $this->id; }
    public function getEmail(): string        { return $this->email; }
    public function getUsername(): string     { return $this->email; }
    public function getUserIdentifier(): string { return $this->email; }
    public function getPassword(): string     { return $this->password; }
    public function getPlainPassword(): ?string { return $this->plainPassword; }
    public function getSalt(): ?string        { return $this->salt; }
    public function getRoles(): array         { return ['ROLE_USER']; }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;
        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
        ]);
    }

    public function unserialize(string $serialized): void
    {
        [
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
        ] = unserialize($serialized);
    }
}
