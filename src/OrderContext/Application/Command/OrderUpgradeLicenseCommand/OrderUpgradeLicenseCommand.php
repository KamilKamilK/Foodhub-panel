<?php declare(strict_types=1);

namespace App\OrderContext\Application\Command\OrderUpgradeLicenseCommand;

use App\OrderContext\Application\DTO\LicenseOrder\LicenseOrderDTO;

class OrderUpgradeLicenseCommand
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
