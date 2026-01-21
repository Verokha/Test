<?php

namespace App\Types\String;

use InvalidArgumentException;

final class Domain extends AbstractStringType
{
    public const int MAX_LENGTH = 255;

    public function __construct(string $value)
    {
        if (! self::isValid($value)) {
            throw new InvalidArgumentException('Domain is not valid.');
        }

        $this->value = mb_strtolower($value);
    }

    public static function isValid(string $value): bool
    {
        if (strlen($value) > self::MAX_LENGTH) {
            return false;
        }

        if (filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            return false;
        }

        return true;
    }
}
