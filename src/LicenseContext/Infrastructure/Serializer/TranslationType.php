<?php declare(strict_types=1);

namespace App\LicenseContext\Infrastructure\Serializer;

use App\LicenseContext\Domain\Entity\LicenseTranslation;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class TranslationType implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return $object;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof LicenseTranslation;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            LicenseTranslation::class => true,
        ];
    }
}
