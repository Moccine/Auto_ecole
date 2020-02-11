<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsValidPassword extends Constraint
{
    public $message = 'app.text.password';

    public function validatedBy()
    {
        return \get_class($this) . 'Validator';
    }
}
