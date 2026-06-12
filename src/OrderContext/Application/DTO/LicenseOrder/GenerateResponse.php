<?php declare(strict_types=1);

namespace App\OrderContext\Application\DTO\LicenseOrder;

use App\Shared\Application\DTO\BaseDTO;

class GenerateResponse extends BaseDTO
{
    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }
}
