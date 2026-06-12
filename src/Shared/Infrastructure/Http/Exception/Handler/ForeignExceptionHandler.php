<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Exception\Handler;

use App\Shared\Application\DTO\ExceptionResponseDTO;
use App\Shared\Infrastructure\Http\Exception\Handler\ExceptionHandlerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ForeignExceptionHandler implements ExceptionHandlerInterface
{
    private array $parameters = [];

    public function __construct(
        private ParameterBagInterface $parameterBag,
        private TranslatorInterface $translator,
    ) {
    }

    public function supports(\Exception $exception): bool
    {
        return $exception instanceof \Exception;
    }

    public function handle(\Exception $exception): ExceptionResponseDTO
    {
        $dir = $this->parameterBag->get('kernel.project_dir');
        $this->parameters = Yaml::parseFile($dir . '/src/Shared/Infrastructure/Resources/foreign_exception.yml')['parameters'];

        $class = get_class($exception);
        if (isset($this->parameters[$class])) {
            $param   = $this->parameters[$class];
            $message = $this->translator->trans(sprintf('exception.%s', $param['code']), [], 'exception');

            return new ExceptionResponseDTO(
                (string) $param['code'],
                $message,
                $param['httpCode'],
                [$exception->getMessage()],
            );
        }

        return new ExceptionResponseDTO(
            (string) $exception->getCode(),
            $exception->getMessage(),
            Response::HTTP_INTERNAL_SERVER_ERROR,
        );
    }
}
