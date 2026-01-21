<?php

namespace App\Site\Domain\Repositories;

use App\Shared\Domain\Repositories\BaseRepositoryInterface;
use App\Site\Domain\Models\Site;

/**
 * @template T of Site
 *
 * @extends BaseRepositoryInterface<T>
 */
interface SiteRepositoryInterface extends BaseRepositoryInterface {}
