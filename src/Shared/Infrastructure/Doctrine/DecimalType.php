<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use App\Shared\Domain\ValueObject\Decimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DecimalType extends Type
{
    private const DECIMAL_TYPE = 'decimalType';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDecimalTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Decimal) {
            return $value->getValue();
        }

        return (string) $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Decimal
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Decimal) {
            return $value;
        }

        $stringValue = (string) $value;

        return new Decimal($stringValue, $this->resolveScale($stringValue));
    }

    public function getName(): string
    {
        return self::DECIMAL_TYPE;
    }

    private function resolveScale(string $value): int
    {
        $separatorPosition = strrpos($value, '.');

        if ($separatorPosition === false) {
            return 0;
        }

        return strlen($value) - $separatorPosition - 1;
    }
}
