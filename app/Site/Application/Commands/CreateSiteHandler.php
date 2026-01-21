<?php

namespace App\Site\Application\Commands;

use App\User\Domain\Models\User;
use App\Site\Domain\Models\Site;
use App\Types\String\Domain;
use App\User\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateSiteHandler
{
    /**
     * @param  UserRepositoryInterface<User>  $userRepository
     */
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function __invoke(CreateSiteCommand $command): Site
    {
        if (! $this->userRepository->exists($command->userId)) {
            throw new ModelNotFoundException(sprintf('User with id %s not found', $command->userId));
        }

        $site = new Site;
        $site->domain = new Domain($command->domain);
        $site->is_active = $command->isActive;
        $site->user_id = $command->userId;
        $site->save();

        return $site;
    }
}
