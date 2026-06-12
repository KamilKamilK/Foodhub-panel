<?php declare(strict_types=1);

namespace App\OrderContext\Application\Command\OrderCommand;

use App\OrderContext\Application\DTO\LicenseOrder\LicenseOrderDTO;

class OrderCommand
{
    private readonly LicenseOrderDTO $request;

    public function __construct(LicenseOrderDTO $request)
    {
        $this->request = $request;
    }

    public function getRequest(): LicenseOrderDTO
    {
        return $this->request;
    }
}
