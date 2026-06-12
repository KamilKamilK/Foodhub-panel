<?php declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\DecimalType\UnexpectedValueException;

class Decimal
{
    private const DEFAULT_SCALE = 2;
    private const NUMBER_LIMIT = 100000000;

    /**
     * @var string
     */
    private $value;

    /**
     * @var integer
     */
    private $scale;

    public function __construct(string $value, int $scale = self::DEFAULT_SCALE)
    {
        $this->value = self::isValidNumber($value);
        $this->scale = self::validScale($scale);
        $this->value = self::isValidNumberLimit($value, $this->scale);

        $this->fix();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function isValidNumber(string $value): string
    {
        if (!is_numeric($value)) {
            throw new UnexpectedValueException();
        }
        
        return $value;
    }

    private static function isValidNumberLimit(string $value, int $scale): string
    {
        if (round((float)$value, $scale) >= self::NUMBER_LIMIT) {
            throw new UnexpectedValueException();   
        }
        
        return $value;
    }
    
    public function getScale(): int
    {
        return $this->scale;
    }

    public function setScale(int $scale): Decimal
    {
        $this->scale = self::validScale($scale);

        $this->fix();
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getValueAsFloat(): float
    {
        return (float)$this->value;
    }

    private function parseScale(?int $scale, Decimal $decimal): int
    {
        return $scale ? self::validScale($scale) : max($this->scale, $decimal->scale);
    }

    private static function validScale(int $scale): int
    {
        return $scale >= 0 ? $scale : self::DEFAULT_SCALE;
    }

    private function fix(): void
    {
        $this->value = ($this->value[0] !== '-') ? $this->roundUp() : $this->roundDown();
    }

    private function roundUp(): string
    {
        return bcadd($this->value, '0.' . str_repeat('0', $this->scale) . '5', $this->scale);
    }

    private function roundDown(): string
    {
        return bcsub($this->value, '0.' . str_repeat('0', $this->scale) . '5', $this->scale);
    }

    public function add(Decimal $component, int $scale = null): Decimal
    {
        $new = clone $this;
        $scale = $new->parseScale($scale, $component);

        $new->value = bcadd($new->value, $component->value, $scale + 1);
        $new->setScale($scale);

        return $new;
    }

    public function sub(Decimal $subtrahend, int $scale = null): Decimal
    {
        $new = clone $this;
        $scale = $new->parseScale($scale, $subtrahend);

        $new->value = bcsub($new->value, $subtrahend->value, $scale + 1);
        $new->setScale($scale);

        return $new;
    }

    public function mul(Decimal $multiplier, int $scale = null): Decimal
    {
        $new = clone $this;
        $scale = $new->parseScale($scale, $multiplier);

        $new->value = bcmul($new->value, $multiplier->value, $scale + 1);
        $new->setScale($scale);

        return $new;
    }

    public function div(Decimal $divisor, int $scale = null): Decimal
    {
        $new = clone $this;
        $scale = $new->parseScale($scale, $divisor);

        $new->value = bcdiv($new->value, $divisor->value, $scale + 1);
        $new->setScale($scale);

        return $new;
    }

    public function set(Decimal $decimal): self
    {
        $this->value = $decimal->value;
        $this->scale = $decimal->scale;

        return $this;
    }
}
