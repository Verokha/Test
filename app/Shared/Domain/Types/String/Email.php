<?php

namespace App\Shared\Domain\Types\String;

use InvalidArgumentException;

final class Email extends AbstractStringType
{
    public const int MIN_LENGTH = 5;

    public const int MAX_LENGTH = 225;

    public function __construct(string $value)
    {
        if (! self::isValid($value)) {
            throw new InvalidArgumentException('Email is not valid.');
        }

        $this->value = $value;
    }

    public static function isValid(string $value): bool
    {
        $length = mb_strlen($value);

        return $length >= self::MIN_LENGTH &&
            $length <= self::MAX_LENGTH &&
            filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
