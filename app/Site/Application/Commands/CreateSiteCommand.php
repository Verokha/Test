<?php

namespace App\Site\Application\Commands;

class CreateSiteCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly string $domain,
        public readonly bool $isActive = false
    ) {}
}
