<?php

namespace App\Site\Domain\Repositories;

use App\Shared\Domain\Repositories\BaseRepositoryInterface;
use App\Site\Domain\Models\Site;
use App\User\Domain\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T of Site
 *
 * @extends BaseRepositoryInterface<T>
 */
interface SiteRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, Site>
     */
    public function orchidListForUser(User $user): LengthAwarePaginator;
}
