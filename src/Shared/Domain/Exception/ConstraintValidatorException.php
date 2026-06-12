<?php declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ConstraintValidatorException extends \Exception
{
    public function __construct(
        private readonly ConstraintViolationListInterface $constraintViolationList,
    ) {
        parent::__construct();
    }

    public function getAppCode(): string
    {
        return 'common.constraint_validator';
    }

    public function getHttpCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
