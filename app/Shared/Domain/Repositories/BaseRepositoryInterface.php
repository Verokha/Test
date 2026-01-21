<?php

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Models\BaseEntity;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @template T of BaseEntity
 */
interface BaseRepositoryInterface
{
    /**
     * @return T|null
     */
    public function findById(int $id): ?BaseEntity;

    /**
     * @return T
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): BaseEntity;

    public function exists(int $id): bool;
}
