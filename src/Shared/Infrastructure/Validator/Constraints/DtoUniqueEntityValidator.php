<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Validator\Constraints;

use App\Shared\Application\DTO\BaseDTO;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DtoUniqueEntityValidator extends ConstraintValidator
{
    private ?DtoUniqueEntity $constraint = null;
    private ?ObjectManager $em = null;
    private ?ClassMetadata $entityMeta = null;
    private ?ObjectRepository $repository = null;
    private ?BaseDTO $validationObject = null;

    public function __construct(private ManagerRegistry $registry)
    {
    }

    public function validate(mixed $object, Constraint $constraint): void
    {
        $this->validationObject = $object;
        $this->constraint       = $constraint;
        $this->checkTypes();

        $this->entityMeta = $this->getEntityManager()->getClassMetadata($this->constraint->entityClass);
        $criteria         = $this->getCriteria();

        if (empty($criteria)) {
            return;
        }

        $result = $this->checkConstraint($criteria);

        if (!$result || (1 === \count($result) && current($result) === $this->entityMeta)) {
            return;
        }

        $objectFields = array_keys($this->constraint->fieldMapping);
        $errorPath    = $this->constraint->errorPath ?? $objectFields[0];
        $invalidValue = $criteria[$this->constraint->fieldMapping[$errorPath]]
            ?? $criteria[$this->constraint->fieldMapping[0]];

        $this->context->buildViolation($this->constraint->message)
            ->atPath($errorPath)
            ->setParameter('{{ value }}', $this->formatWithIdentifiers($invalidValue))
            ->setInvalidValue($invalidValue)
            ->setCode(DtoUniqueEntity::NOT_UNIQUE_ERROR)
            ->setCause($result)
            ->addViolation();
    }

    private function checkTypes(): void
    {
        if (!$this->validationObject instanceof BaseDTO) {
            throw new UnexpectedTypeException($this->validationObject, BaseDTO::class);
        }
        if (!$this->constraint instanceof DtoUniqueEntity) {
            throw new UnexpectedTypeException($this->constraint, DtoUniqueEntity::class);
        }
        if (!$this->constraint->entityClass || !\class_exists($this->constraint->entityClass)) {
            throw new UnexpectedTypeException($this->constraint->entityClass, Entity::class);
        }
        if (empty($this->constraint->fieldMapping)) {
            throw new UnexpectedTypeException($this->constraint->fieldMapping, '[objectProperty => entityProperty]');
        }
    }

    private function getEntityManager(): ObjectManager
    {
        if ($this->em !== null) {
            return $this->em;
        }

        if ($this->constraint->em) {
            $this->em = $this->registry->getManager($this->constraint->em);
            if (!$this->em) {
                throw new ConstraintDefinitionException(
                    sprintf('Object manager "%s" does not exist.', $this->constraint->em)
                );
            }
        } else {
            $this->em = $this->registry->getManagerForClass($this->constraint->entityClass);
            if (!$this->em) {
                throw new ConstraintDefinitionException(
                    sprintf('Unable to find the object manager for class "%s".', $this->constraint->entityClass)
                );
            }
        }

        return $this->em;
    }

    private function getCriteria(): array
    {
        $validationClass = new \ReflectionClass($this->validationObject);
        $criteria = [];

        foreach ($this->constraint->fieldMapping as $objectField => $entityField) {
            if (!$validationClass->hasProperty($objectField)) {
                throw new ConstraintDefinitionException(sprintf(
                    'Property "%s" does not exist on the DTO.',
                    $objectField,
                ));
            }
            if (!property_exists($this->constraint->entityClass, $entityField)) {
                throw new ConstraintDefinitionException(sprintf(
                    'Property "%s" does not exist in entity class.',
                    $entityField,
                ));
            }
            if (!$this->entityMeta->hasField($entityField) && !$this->entityMeta->hasAssociation($entityField)) {
                throw new ConstraintDefinitionException(sprintf(
                    'Field "%s" is not mapped by Doctrine.',
                    $entityField,
                ));
            }

            $fieldValue = $validationClass->getProperty($objectField)->getValue($this->validationObject);

            if ($fieldValue === null && !$this->constraint->ignoreNull) {
                throw new ConstraintDefinitionException('Unique value cannot be NULL');
            }

            $criteria[$entityField] = $fieldValue;

            if ($criteria[$entityField] !== null && $this->entityMeta->hasAssociation($entityField)) {
                $this->getEntityManager()->initializeObject($criteria[$entityField]);
            }
        }

        return $criteria;
    }

    private function checkConstraint(array $criteria): array
    {
        $result = $this->getRepository()->{$this->constraint->repositoryMethod}($criteria);

        if ($result instanceof \IteratorAggregate) {
            $result = $result->getIterator();
        }

        if ($result instanceof \Iterator) {
            $result->rewind();
            if ($result instanceof \Countable && \count($result) > 1) {
                $result = [$result->current(), $result->current()];
            } else {
                $current = $result->current();
                $result  = $current === null ? [] : [$current];
            }
        } elseif (\is_array($result)) {
            reset($result);
        } else {
            $result = $result === null ? [] : [$result];
        }

        return $result;
    }

    private function formatWithIdentifiers(mixed $value): string
    {
        if (!is_object($value) || $value instanceof \DateTimeInterface) {
            return $this->formatValue($value, self::PRETTY_DATE);
        }

        $idClass = get_class($value);

        if ($this->entityMeta->getName() !== $idClass) {
            $identifiers = $this->getEntityManager()->getMetadataFactory()->hasMetadataFor($idClass)
                ? $this->getEntityManager()->getClassMetadata($idClass)->getIdentifierValues($value)
                : [];
        } else {
            $identifiers = $this->entityMeta->getIdentifierValues($value);
        }

        if (!$identifiers) {
            return sprintf('object("%s")', $idClass);
        }

        array_walk($identifiers, function (mixed &$id, string $field): void {
            $idAsString = (!is_object($id) || $id instanceof \DateTimeInterface)
                ? $this->formatValue($id, self::PRETTY_DATE)
                : sprintf('object("%s")', get_class($id));
            $id = sprintf('%s => %s', $field, $idAsString);
        });

        return sprintf('object("%s") identified by (%s)', $idClass, implode(', ', $identifiers));
    }

    private function getRepository(): ObjectRepository
    {
        if ($this->repository === null) {
            $this->repository = $this->getEntityManager()->getRepository($this->constraint->entityClass);
        }
        return $this->repository;
    }
}
