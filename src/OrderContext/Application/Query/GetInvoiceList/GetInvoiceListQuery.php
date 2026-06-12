<?php declare(strict_types=1);

namespace App\OrderContext\Application\Query\GetInvoiceList;

use App\Shared\Domain\ValueObject\Subdomain;

class GetInvoiceListQuery
{
    public function __construct(private readonly Subdomain $subdomain)
    {
    }

    public function getSubdomain(): Subdomain
    {
        return $this->subdomain;
    }
}
