<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Form\Validator\SpecialCode;

use App\MerchantContext\Domain\Repository\MerchantRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;

class SpecialCodeValidator extends ConstraintValidator
{
    public function __construct(
        private MerchantRepositoryInterface $merchantRepository,
        private TranslatorInterface $translator,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$this->merchantRepository->findOneBySpecialCode($value)) {
            $this->context->addViolation(
                $this->translator->trans($constraint->message, [], 'validators')
            );
        }
    }
}
