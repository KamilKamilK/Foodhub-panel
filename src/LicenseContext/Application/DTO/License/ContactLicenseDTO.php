<?php declare(strict_types=1);

namespace App\LicenseContext\Application\DTO\License;

use App\Shared\Application\DTO\BaseDTO;

class ContactLicenseDTO extends BaseDTO
{
    /**
     * @var ?string
     */
    public ?string $subdomain = null;

    /**
     * @var ?string
     */
    public ?string $contactPhone = null;
}
