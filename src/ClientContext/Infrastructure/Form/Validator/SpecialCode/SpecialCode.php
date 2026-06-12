<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Form\Validator\SpecialCode;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class SpecialCode extends Constraint
{
    public function __construct(
        public readonly string $message = 'common.incorrect_special_code',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options ?? [], $groups, $payload);
    }

    public function validatedBy(): string
    {
        return SpecialCodeValidator::class;
    }
}
