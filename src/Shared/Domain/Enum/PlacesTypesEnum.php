<?php declare(strict_types=1);

namespace App\Shared\Domain\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;

enum PlacesTypesEnum: string
{
    case OTHER = 'other';
    case RESTAURANT = 'restaurant';
    case PIZZERIA = 'pizzeria';
    case FOOD_TRUCK = 'food-truck';
    case CAFE = 'cafe';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getTranslatedFormChoices(TranslatorInterface $translator): array
    {
        $types = [];
        foreach (self::cases() as $case) {
            $types[$translator->trans(sprintf('places.types.%s', $case->value))] = $case->value;
        }
        return $types;
    }
}
