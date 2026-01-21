<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories;

use App\Shared\Domain\Models\BaseEntity;
use App\Shared\Domain\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @template T of BaseEntity
 *
 * @implements BaseRepositoryInterface<T>
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /** @var class-string<T> */
    protected string $model;

    public function findById(int $id): ?BaseEntity
    {
        /** @var T|null $result */
        $result = $this->model::find($id);

        return $result;
    }

    public function getById(int $id): BaseEntity
    {
        $result = $this->findById($id);

        if ($result === null) {
            throw new ModelNotFoundException('Entity '.$this->model.' not found.');
        }

        return $result;
    }

    public function exists(int $id): bool
    {
        return $this->query()
            ->where($this->getEntityKeyName(), $id)
            ->exists();
    }

    /**
     * @return Builder<BaseEntity>
     */
    protected function query(): Builder
    {
        /** @var class-string<BaseEntity> $modelClass */
        $modelClass = $this->model;

        /** @var Builder<BaseEntity> $query */
        $query = $modelClass::query();

        return $query;
    }

    protected function getEntityKeyName(): string
    {
        return (new $this->model)->getKeyName();
    }
}
