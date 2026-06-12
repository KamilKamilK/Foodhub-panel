<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Exception\Handler;

use App\Shared\Application\DTO\ConstraintViolationDTO;
use App\Shared\Application\DTO\ExceptionResponseDTO;
use App\Shared\Domain\Exception\ConstraintValidatorException;
use App\Shared\Infrastructure\Http\Exception\Handler\ExceptionHandlerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ConstraintValidatorExceptionHandler implements ExceptionHandlerInterface
{
    private array $parameters = [];

    public function __construct(
        private TranslatorInterface $translator,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function supports(\Exception $exception): bool
    {
        return $exception instanceof ConstraintValidatorException;
    }

    public function handle(\Exception $exception): ExceptionResponseDTO
    {
        /** @var ConstraintValidatorException $exception */
        $dir = $this->parameterBag->get('kernel.project_dir');
        $this->parameters = Yaml::parseFile($dir . '/src/Shared/Infrastructure/Resources/constraint_violation.yml')['parameters'];

        $message = $this->translator->trans(
            sprintf('exception.%s', $exception->getAppCode()), [], 'exception'
        );

        return new ExceptionResponseDTO(
            $exception->getAppCode(),
            $message,
            $exception->getHttpCode(),
            [],
            $this->handleValidatorErrors($exception->getConstraintViolationList()),
        );
    }

    private function handleValidatorErrors(ConstraintViolationListInterface $violationList): array
    {
        $errors = [];

        /** @var ConstraintViolationInterface $error */
        foreach ($violationList as $error) {
            $constraint = $error->getConstraint();
            $class      = get_class($constraint);

            if (!isset($this->parameters[$class])) {
                continue;
            }

            $code      = $this->parameters[$class]['code'];
            $shortCode = explode('.', $code)[1];
            $message   = $this->translator->trans(
                sprintf('exception.%s', $code),
                $this->prepareConstraintVariables($constraint),
                'exception',
            );

            $errors[$error->getPropertyPath()][] = new ConstraintViolationDTO($shortCode, $message);
        }

        return $errors;
    }

    private function prepareConstraintVariables(object $constraint): array
    {
        $result = [];
        foreach (get_object_vars($constraint) as $key => $var) {
            $result["{{ {$key} }}"] = $var;
        }
        return $result;
    }
}
