<?php declare(strict_types=1);

namespace App\LicenseContext\Application\Query\GetList;

use App\LicenseContext\Domain\Repository\LicenseRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetListQueryHandler
{
    public function __construct(
        private LicenseRepositoryInterface $licenseRepository,
    ) {
    }

    public function __invoke(GetListQuery $_query): array
    {
        return $this->licenseRepository->findAllActiveAndVisible();
    }
}
