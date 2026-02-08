<?php

namespace App\Site\Application\Commands;

use App\Site\Domain\Models\Site;

/**
 * @phpstan-import-type SeoItem from Site
 */
class CreateSiteCommand
{
    /**
     * @param  SeoItem  $seo
     */
    public function __construct(
        public readonly int $userId,
        public readonly string $domain,
        public readonly string $name,
        public readonly string $telegramChannel,
        public readonly array $seo,
        public readonly bool $enabled = false,
        public readonly ?bool $blocked = null,
        public readonly ?string $blockReason = null,
    ) {}

    /**
     * @param  array{domain: string, name: string, telegram_channel: string, seo: SeoItem, enabled: string, blocked: ?string, block_reason: ?string}  $data
     */
    public static function fromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            domain: $data['domain'],
            name: $data['name'],
            telegramChannel: $data['telegram_channel'],
            seo: $data['seo'],
            enabled: (bool) $data['enabled'],
            blocked: isset($data['blocked']) ? (bool) $data['blocked'] : null,
            blockReason: $data['block_reason'] ?? null,
        );
    }
}
