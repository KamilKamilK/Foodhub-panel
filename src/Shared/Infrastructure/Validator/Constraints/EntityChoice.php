<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class EntityChoice extends Constraint
{
    public function __construct(
        public readonly string $entityClass = '',
        public readonly ?string $method = null,
        public readonly bool $multiple = false,
        public readonly bool $strict = true,
        public readonly ?int $min = null,
        public readonly ?int $max = null,
        public readonly string $message = 'The value you selected is not a valid choice.',
        public readonly string $multipleMessage = 'One or more of the given values is invalid.',
        public readonly string $minMessage = 'You must select at least {{ limit }} choice.|You must select at least {{ limit }} choices.',
        public readonly string $maxMessage = 'You must select at most {{ limit }} choice.|You must select at most {{ limit }} choices.',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options ?? [], $groups, $payload);
    }

    public function validatedBy(): string
    {
        return EntityChoiceValidator::class;
    }
}
