<?php declare(strict_types=1);

namespace App\OrderContext\Application\DTO\LicenseOrder;

use App\LicenseContext\Domain\Enum\PeriodEnum;
use OpenApi\Annotations as OA;

class LicenseOrderDTO extends AbstractLicenseOrderDTO
{
    /**
     * @var ?int
     */
    public ?int $licenseId = null;

    /**
     * @var ?int
     */
    public ?int $selectedSetId = null;

    /**
     * @var ?string|PeriodEnum
     *
     * @OA\Property(
     *     enum={
     *     PeriodEnum::MONTH,
     *     PeriodEnum::YEAR
     *     }
     * )
     */
    public ?string $period = null;
}
