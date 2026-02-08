<?php

declare(strict_types=1);

namespace App\Shared\Application\Rules;

use App\Shared\Domain\Types\String\Domain;
use Illuminate\Contracts\Validation\ValidationRule;

class DomainRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! is_string($value) || ! Domain::isValid($value)) {
            $fail('Домен не валиден.', null);
        }
    }
}
