<?php

namespace App\Shared\Application\Rules;

use App\Shared\Domain\Types\String\TelegramChannel;
use Illuminate\Contracts\Validation\ValidationRule;

class TelegramChannelRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! is_string($value) || ! TelegramChannel::isValid($value)) {
            $fail('Не корректная ссылка на телеграм канал', null);
        }
    }
}
