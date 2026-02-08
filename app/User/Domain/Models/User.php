<?php

namespace App\User\Domain\Models;

use App\Shared\Domain\Types\Casts\StringTypeCast;
use App\Shared\Domain\Types\Enum\Role;
use App\Shared\Domain\Types\String\Email;
use App\Site\Domain\Models\Site;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property Email $email
 * @property array<string, string> $permissions
 * @property Collection<int, Site> $sites
 */
class User extends Authenticatable
{
    use Searchable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'domain' => StringTypeCast::class.':'.Email::class,
        'permissions' => 'array',
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var class-string[]
     */
    protected $allowedFilters = [
        'id' => Where::class,
        'name' => Like::class,
        'email' => Like::class,
        'updated_at' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * @var string[]
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    /**
     * @return HasMany<Site, $this>
     */
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->inRole(Role::SaAdmin->value);
    }
}
