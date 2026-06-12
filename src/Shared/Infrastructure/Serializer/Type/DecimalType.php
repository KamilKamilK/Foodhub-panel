<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Serializer\Type;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;
use App\Shared\Domain\ValueObject\Decimal;

class DecimalType implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'decimalType',
                'method' => 'serializeDecimalToJson',
            ],
        ];
    }

    public function serializeDecimalToJson(JsonSerializationVisitor $visitor, Decimal $decimal, array $type, Context $context)
    {
        $decimal = isset($type["params"][0]) ? $decimal->setScale($type["params"][0]) : $decimal;
        return $decimal->getValueAsFloat();
    }
}
