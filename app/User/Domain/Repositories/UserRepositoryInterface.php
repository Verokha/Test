<?php

namespace App\User\Domain\Repositories;

use App\User\Domain\Models\User;

/**
 * @template T of User
 */
interface UserRepositoryInterface
{
    public function exists(int $id): bool;
}
