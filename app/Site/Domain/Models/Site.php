<?php

namespace App\Site\Domain\Models;

use App\Shared\Domain\Models\BaseEntity;
use App\Shared\Domain\Types\Casts\StringTypeCast;
use App\Shared\Domain\Types\String\CustomString;
use App\Shared\Domain\Types\String\Domain;
use App\Shared\Domain\Types\String\TelegramChannel;
use App\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Orchid\Screen\AsSource;

/**
 * @phpstan-type SeoItem array{title: ?string, description: ?string, h1: ?string}
 *
 * @property int $id
 * @property Domain $domain
 * @property TelegramChannel $telegram_channel
 * @property SeoItem $seo
 * @property CustomString $name
 * @property bool $enabled
 * @property bool $blocked
 * @property ?CustomString $block_reason
 * @property ?Carbon $blocked_at
 * @property int $user_id
 * @property User $user
 */
class Site extends BaseEntity
{
    use AsSource;

    protected $casts = [
        'domain' => StringTypeCast::class.':'.Domain::class,
        'telegram_channel' => StringTypeCast::class.':'.TelegramChannel::class,
        'seo' => 'array',
        'name' => StringTypeCast::class.':'.CustomString::class,
        'block_reason' => StringTypeCast::class.':'.CustomString::class,
        'enabled' => 'boolean',
        'blocked' => 'boolean',
    ];

    protected $fillable = [
        'domain',
        'is_active',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
