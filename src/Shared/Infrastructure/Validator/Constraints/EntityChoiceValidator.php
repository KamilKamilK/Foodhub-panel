<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Validator\Constraints;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EntityChoiceValidator extends ConstraintValidator
{

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityChoice) {
            throw new UnexpectedTypeException($constraint, EntityChoice::class);
        }

        if (null === $value) {
            return;
        }
        $repository = $this->registry->getRepository($constraint->entityClass);
        if (!is_subclass_of($repository, ServiceEntityRepository::class)) {
            throw new ConstraintDefinitionException(
                sprintf('"%s" expects to be an instance of %s',
                    $constraint->entityClass,
                    ServiceEntityRepository::class
                )
            );
        }

        if ($constraint->multiple && !\is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        if (!$constraint->multiple && !is_int($value)) {
            throw new UnexpectedValueException($value, 'int');
        }

        if ($constraint->method) {
            if (!method_exists($repository, $constraint->method)) {
                throw new \Exception(sprintf('Method: s% does not exist in %s', $constraint->method, get_class($repository)));
            }

            $method = $constraint->method;
            $choices = $repository->$method(['id' => $value]);
        } else {
            $choices = $repository->findBy(['id' => $value]);
        }
        $choices = new ArrayCollection($choices);
        $choices = $choices->map(function ($e) {
            return $e->getId();
        })->toArray();

        if (true !== $constraint->strict) {
            throw new \RuntimeException('The "strict" option of the Choice constraint should not be used.');
        }

        if ($constraint->multiple) {
            foreach ($value as $_value) {
                if (!\in_array($_value, $choices, true)) {
                    $this->context->buildViolation($constraint->multipleMessage)
                        ->setParameter('{{ value }}', $this->formatValue($_value))
                        ->setCode(Choice::NO_SUCH_CHOICE_ERROR)
                        ->setInvalidValue($_value)
                        ->addViolation();

                    return;
                }
            }

            $count = \count($value);

            if (null !== $constraint->min && $count < $constraint->min) {
                $this->context->buildViolation($constraint->minMessage)
                    ->setParameter('{{ limit }}', $constraint->min)
                    ->setPlural((int)$constraint->min)
                    ->setCode(Choice::TOO_FEW_ERROR)
                    ->addViolation();

                return;
            }

            if (null !== $constraint->max && $count > $constraint->max) {
                $this->context->buildViolation($constraint->maxMessage)
                    ->setParameter('{{ limit }}', $constraint->max)
                    ->setPlural((int)$constraint->max)
                    ->setCode(Choice::TOO_MANY_ERROR)
                    ->addViolation();

                return;
            }
        } elseif (!\in_array($value, $choices, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Choice::NO_SUCH_CHOICE_ERROR)
                ->addViolation();
        }
    }
}
