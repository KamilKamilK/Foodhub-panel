<?php declare(strict_types=1);

namespace App\OrderContext\Application\DTO\LicenseOrder;

use App\Shared\Application\DTO\BaseDTO;
use App\Shared\Domain\Enum\CurrencyEnum;
use App\OrderContext\Domain\Enum\PaymentMethodTypeEnum;
use OpenApi\Annotations as OA;

class LicenseOrderPaymentDTO extends BaseDTO
{
    /**
     * @var ?string|PaymentMethodTypeEnum
     *
     * @OA\Property(enum={
     *     PaymentMethodTypeEnum::CARD_TOKEN,
     *     PaymentMethodTypeEnum::BLIK,
     *     PaymentMethodTypeEnum::OTHER
     * })
     */
    public ?string $methodType = null;

    /**
     * @var ?string
     */
    public ?string $methodValue = null;

    /**
     * @var ?float
     */
    public ?float $totalPriceNet = null;

    /**
     * @var ?string|CurrencyEnum
     *
     * @OA\Property(enum={
     *      CurrencyEnum::PLN,
     *      CurrencyEnum::EUR,
     *      CurrencyEnum::USD,
     * })
     */
    public ?string $currency = null;

    /**
     * @var ?string
     */
    public ?string $continueUrl = null;
}
