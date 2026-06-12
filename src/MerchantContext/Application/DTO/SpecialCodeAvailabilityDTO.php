<?php declare(strict_types=1);

namespace App\MerchantContext\Application\DTO;

use App\Shared\Application\DTO\BaseDTO;

class SpecialCodeAvailabilityDTO extends BaseDTO
{
    public function __construct(public readonly bool $isAvailable)
    {
    }
}
