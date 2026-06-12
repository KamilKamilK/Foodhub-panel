<?php declare(strict_types=1);

namespace App\MerchantContext\Domain\Repository;

use App\MerchantContext\Domain\Entity\Merchant;

interface MerchantRepositoryInterface
{
    /** @return Merchant[] */
    public function findAll(): array;

    public function findOneBySpecialCode(string $specialCode): ?Merchant;

    /** @return Merchant[] */
    public function findDefault(): array;
}
