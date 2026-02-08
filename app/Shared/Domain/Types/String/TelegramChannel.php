<?php

namespace App\Shared\Domain\Types\String;

use InvalidArgumentException;

class TelegramChannel extends AbstractStringType
{
    public const int MIN_LENGTH = 1;

    public const int MAX_LENGTH = 32;

    private const string PREFIX = 't.me/';

    public function __construct(string $value)
    {
        if (! self::isValid($value)) {
            throw new InvalidArgumentException('Telegram channel link is not valid.');
        }

        $this->value = $value;
    }

    public static function isValid(string $value): bool
    {
        if (str_starts_with($value, 'http://')) {
            return false;
        }

        $value = self::normalize($value);

        if (! str_starts_with($value, self::PREFIX)) {
            return false;
        }
        $username = substr($value, strlen(self::PREFIX));
        $length = mb_strlen($username);

        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            return false;
        }

        return (bool) preg_match('/^[a-z0-9_]+$/', $username);
    }

    private static function normalize(string $value): string
    {
        if (str_starts_with($value, 'https://')) {
            return substr($value, 8);
        }

        return $value;
    }
}
