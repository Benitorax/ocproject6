<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use App\Service\VideoUrlConverter\VideoUrlConverter;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class VideoUrlValidator extends ConstraintValidator
{
    private VideoUrlConverter $converter;

    public function __construct(VideoUrlConverter $converter)
    {
        $this->converter = $converter;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof VideoUrl) {
            throw new UnexpectedTypeException($constraint, VideoUrl::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        if ($this->converter->supports($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->atPath('tagOrUrl')
            ->addViolation();
    }
}
