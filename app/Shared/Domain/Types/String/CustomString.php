<?php

declare(strict_types=1);

namespace App\Shared\Domain\Types\String;

use InvalidArgumentException;

final class CustomString extends AbstractStringType
{
    public const int MIN_LENGTH = 1;

    public const int MAX_LENGTH = 255;

    public function __construct(string $value)
    {
        if (! self::isValid($value)) {
            throw new InvalidArgumentException('Custom string is not valid.');
        }

        $this->value = $value;
    }

    public static function isValid(string $value): bool
    {
        $strlen = mb_strlen($value);

        return $strlen >= self::MIN_LENGTH && $strlen <= self::MAX_LENGTH;
    }
}
