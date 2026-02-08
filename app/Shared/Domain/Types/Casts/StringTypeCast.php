<?php

namespace App\Shared\Domain\Types\Casts;

use App\Shared\Domain\Types\String\AbstractStringType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * @template T of AbstractStringType
 *
 * @implements CastsAttributes<T|null, T|string|null>
 */
class StringTypeCast implements CastsAttributes
{
    /**
     * @var class-string<T>
     */
    private string $typeClass;

    /**
     * @param  class-string<T>  $typeClass
     */
    public function __construct(string $typeClass)
    {
        $this->typeClass = $typeClass;
    }

    public function get(mixed $model, string $key, mixed $value, array $attributes): ?AbstractStringType
    {
        if ($value === null) {
            return null;
        }

        return new $this->typeClass($value);
    }

    public function set(mixed $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof AbstractStringType) {
            return (string) $value;
        }

        if ($this->typeClass::isValid($value)) {
            return $value;
        }

        return null;
    }
}
