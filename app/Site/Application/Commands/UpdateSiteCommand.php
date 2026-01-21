<?php

namespace App\Site\Application\Commands;

class UpdateSiteCommand
{
    public function __construct(
        public readonly int $siteId,
        public readonly int $userId,
        public readonly string $domain,
        public readonly bool $isActive = false
    ) {}
}
