<?php

namespace App\Site\Infrastructure\Repositories;

use App\Shared\Domain\Types\Enum\Role;
use App\Shared\Infrastructure\Repositories\BaseRepository;
use App\Site\Domain\Models\Site;
use App\Site\Domain\Repositories\SiteRepositoryInterface;
use App\User\Domain\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T of Site
 *
 * @implements SiteRepositoryInterface<T>
 *
 * @extends BaseRepository<T>
 */
class SiteRepository extends BaseRepository implements SiteRepositoryInterface
{
    protected string $model = Site::class;

    public function orchidListForUser(User $user): LengthAwarePaginator
    {
        $query = Site::query();

        if (! $user->inRole(Role::SaAdmin->value)) {
            $query->where('user_id', $user->id);
        }

        return $query->paginate();
    }
}
