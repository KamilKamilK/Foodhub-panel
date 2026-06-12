<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Entity;

use App\ClientContext\Domain\Entity\Address;
use App\ClientContext\Domain\Entity\Company;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;

class Client
{
    private ?int $id;
    private Company $company;
    private Address $address;
    private ?string $firstname;
    private ?string $lastname;
    private ?string $phone;
    private ?string $email;
    private ?string $regSpecialCode;
    private string $subdomain;
    private string $dbName;
    private string $dbPassword;
    private string $dbUser;
    private ?\DateTime $createdAt;
    private Collection $users;
    private Collection $agreements;
    private Collection $clientLicenses;

    public function __construct()
    {
        $this->users          = new ArrayCollection();
        $this->company        = new Company();
        $this->address        = new Address();
        $this->agreements     = new ArrayCollection();
        $this->clientLicenses = new ArrayCollection();
    }

    public static function fromRegistration(
        string $firstname,
        string $lastname,
        ?string $phone,
        ?string $email,
        ?string $specialCode,
        string $dbUser,
        string $dbName,
        string $dbPassword,
        string $subdomain,
    ): self {
        $client = new self();
        $client->firstname      = $firstname;
        $client->lastname       = $lastname;
        $client->phone          = $phone;
        $client->email          = $email;
        $client->regSpecialCode = $specialCode;
        $client->dbUser         = $dbUser;
        $client->dbName         = $dbName;
        $client->dbPassword     = $dbPassword;
        $client->subdomain      = $subdomain;
        $client->createdAt      = new \DateTime();
        return $client;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function updateEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getRegSpecialCode(): ?string
    {
        return $this->regSpecialCode;
    }

    public function setRegSpecialCode(?string $regSpecialCode): self
    {
        $this->regSpecialCode = $regSpecialCode;

        return $this;
    }

    public function getSubdomain(): ?string
    {
        return $this->subdomain;
    }

    /** @internal For registration/provisioning via fromRegistration() only. */
    public function setSubdomain(string $subdomain): self
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    public function getDbName(): ?string
    {
        return $this->dbName;
    }

    /** @internal For registration/provisioning via fromRegistration() only. */
    public function setDbName(string $dbName): self
    {
        $this->dbName = $dbName;

        return $this;
    }

    public function getDbPassword(): ?string
    {
        return $this->dbPassword;
    }

    /** @internal For registration/provisioning via fromRegistration() only. */
    public function setDbPassword(string $dbPassword): self
    {
        $this->dbPassword = $dbPassword;

        return $this;
    }

    public function getDbUser(): ?string
    {
        return $this->dbUser;
    }

    /** @internal For registration/provisioning via fromRegistration() only. */
    public function setDbUser(string $dbUser): self
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(ClientUser $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(ClientUser $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /** @internal For fixtures/provisioning only. */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAgreements(): Collection
    {
        return $this->agreements;
    }

    public function addAgreement(Agreement $agreement): self
    {
        if (!$this->agreements->contains($agreement)) {
            $this->agreements[] = $agreement;
            $agreement->addClient($this);
        }

        return $this;
    }

    public function removeAgreement(Agreement $agreement): self
    {
        if ($this->agreements->contains($agreement)) {
            $this->agreements->removeElement($agreement);
            $agreement->removeClient($this);
        }

        return $this;
    }

    public function getClientLicenses(): Collection
    {
        return $this->clientLicenses;
    }

    public function addClientLicense(ClientLicense $clientLicense): self
    {
        if (!$this->clientLicenses->contains($clientLicense)) {
            $this->clientLicenses->add($clientLicense);
            $clientLicense->setClient($this);
        }

        return $this;
    }

    public function removeClientLicense(ClientLicense $clientLicense): self
    {
        if ($this->clientLicenses->contains($clientLicense)) {
            $this->clientLicenses->removeElement($clientLicense);
        }

        return $this;
    }

    public function getLatestClientLicense(): ClientLicense
    {
        return $this->clientLicenses->matching(
            new Criteria(null, ['expiredAt' => Order::Descending])
        )->first();
    }

    public function getCurrentClientLicense(): ClientLicense
    {
        $currentLicense = $this->clientLicenses->filter(
            fn(ClientLicense $cl) => $cl->isCurrent()
        )->first();

        return $currentLicense ?: $this->getLatestClientLicense();
    }

    public function getActiveClientLicenses(): Collection
    {
        return $this->clientLicenses
            ->filter(fn(ClientLicense $cl) => $cl->isActive())
            ->matching(new Criteria(null, ['validFrom' => Order::Ascending]));
    }

    public function getFullName(): string
    {
        return trim(($this->firstname ?? '') . ' ' . ($this->lastname ?? ''));
    }

    public function hasActiveLicense(): bool
    {
        return !$this->getActiveClientLicenses()->isEmpty();
    }

    public function isTrialActive(): bool
    {
        $latest = $this->getLatestClientLicense();
        return $latest?->getLicense()->isTrial() && $latest->isActive();
    }
}
