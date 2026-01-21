<?php

namespace App\User\Infrastructure\Repositories;

use App\User\Domain\Models\User;
use App\User\Domain\Repositories\UserRepositoryInterface;

/**
 * @template T of User
 *
 * @implements UserRepositoryInterface<T>
 */
final class UserRepository implements UserRepositoryInterface
{
    public function exists(int $id): bool
    {
        return User::where('id', $id)->exists();
    }
}
