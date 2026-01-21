<?php

namespace App\Site\Infrastructure\Repositories;

use App\Shared\Infrastructure\Repositories\BaseRepository;
use App\Site\Domain\Models\Site;
use App\Site\Domain\Repositories\SiteRepositoryInterface;

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
}
