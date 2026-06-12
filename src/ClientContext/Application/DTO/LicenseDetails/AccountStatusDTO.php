<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\LicenseDetails;

final class AccountStatusDTO
{
    public readonly ?string $expirationDate;
    public readonly ?string $createdAt;
    public readonly bool $isActive;
    public readonly int $posLimit;
    public readonly int $additionalPoses;
    public readonly ?int $menuLimit;
    public readonly bool $includedFoodHubOrder;
    public readonly array $addons;
    public readonly ?ClientLicenseDTO $currentLicense;
    public readonly array $activeLicenses;

    public function __construct(
        \DateTime $expirationDate,
        ?\DateTime $createdAt,
        bool $isActive,
        int $posLimit,
        int $additionalPoses,
        ?int $menuLimit,
        bool $includedFoodHubOrder,
        array $addons,
        ClientLicenseDTO $currentLicense,
        array $activeLicenses,
    ) {
        $this->expirationDate       = $expirationDate->format('Y-m-d');
        $this->createdAt            = $createdAt?->format('Y-m-d');
        $this->isActive             = $isActive;
        $this->posLimit             = $posLimit;
        $this->additionalPoses      = $additionalPoses;
        $this->menuLimit            = $menuLimit;
        $this->includedFoodHubOrder = $includedFoodHubOrder;
        $this->addons               = $addons;
        $this->currentLicense       = $currentLicense;
        $this->activeLicenses       = $activeLicenses;
    }
}
