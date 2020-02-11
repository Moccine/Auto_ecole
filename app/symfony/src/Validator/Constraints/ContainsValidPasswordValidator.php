<?php

namespace App\Validator\Constraints;

use App\Manager\UserManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsValidPasswordValidator extends ConstraintValidator
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsValidPassword) {
            throw new UnexpectedTypeException($constraint, ContainsValidPassword::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        if (!UserManager::isPasswordConstraintsValid($value)) {
            $this->context->buildViolation($this->translator->trans($constraint->message))
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
