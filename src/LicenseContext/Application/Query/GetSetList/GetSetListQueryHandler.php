<?php declare(strict_types=1);

namespace App\LicenseContext\Application\Query\GetSetList;

use App\LicenseContext\Domain\Repository\LicenseSetRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetSetListQueryHandler
{
    public function __construct(
        private LicenseSetRepositoryInterface $licenseSetRepository,
    ) {
    }

    public function __invoke(GetSetListQuery $_query): array
    {
        return $this->licenseSetRepository->findAllWithDependencies();
    }
}
