<?php

namespace App\Site\Domain\Models;

use App\Shared\Domain\Models\BaseEntity;
use App\Types\Casts\StringTypeCast;
use App\Types\String\Domain;
use App\User\Domain\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

/**
 * @property int $id
 * @property Domain $domain
 * @property bool $is_active
 * @property int $user_id
 * @property User $user
 */
class Site extends BaseEntity
{
    use AsSource;

    protected $casts = [
        'domain' => StringTypeCast::class . ':' . Domain::class,
        'is_active' => 'boolean',
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
