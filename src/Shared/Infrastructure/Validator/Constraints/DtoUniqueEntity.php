<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class DtoUniqueEntity extends Constraint
{
    public const NOT_UNIQUE_ERROR = 'e777db8d-3af0-41f6-8a73-55255375cdca';

    protected static array $errorNames = [
        self::NOT_UNIQUE_ERROR => 'NOT_UNIQUE_ERROR',
    ];

    public function __construct(
        public readonly string $entityClass = '',
        public readonly array $fieldMapping = [],
        public readonly ?string $errorPath = null,
        public readonly ?string $em = null,
        public readonly bool $ignoreNull = true,
        public readonly string $message = 'This value is already used.',
        public readonly string $repositoryMethod = 'findBy',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options ?? [], $groups, $payload);
    }

    public function getDefaultOption(): ?string
    {
        return 'entityClass';
    }

    public function getRequiredOptions(): array
    {
        return ['fieldMapping', 'entityClass'];
    }

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return DtoUniqueEntityValidator::class;
    }
}
