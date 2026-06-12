<?php declare(strict_types=1);

namespace App\OrderContext\Application\Command\OrderAddonsCommand;

use App\OrderContext\Application\DTO\LicenseOrder\OrderAddonsDTO;

class OrderAddonsCommand
{
    private readonly OrderAddonsDTO $request;

    public function __construct(OrderAddonsDTO $request)
    {
        $this->request = $request;
    }

    public function getRequest(): OrderAddonsDTO
    {
        return $this->request;
    }
}
