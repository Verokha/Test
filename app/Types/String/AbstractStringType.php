<?php

namespace App\Types\String;

/**
 * @property-read string $value
 */
abstract class AbstractStringType implements \JsonSerializable, \Stringable
{
    protected string $value;

    abstract public function __construct(string $value);

    abstract public static function isValid(string $value): bool;

    public function equals(self $other): bool
    {
        return (string) $this === (string) $other;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __get(string $name): string
    {
        if ($name === 'value') {
            return $this->value;
        }

        throw new \InvalidArgumentException('Unknown property: '.$name);
    }
}
