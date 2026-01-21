<?php

namespace App\Site\Application\Commands;

use App\User\Domain\Models\User;
use App\Site\Domain\Models\Site;
use App\Site\Domain\Repositories\SiteRepositoryInterface;
use App\Types\String\Domain;
use App\User\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateSiteHandler
{
    /**
     * @param  UserRepositoryInterface<User>  $userRepository
     * @param  SiteRepositoryInterface<Site>  $siteRepository
     */
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SiteRepositoryInterface $siteRepository
    ) {}

    public function __invoke(UpdateSiteCommand $command): Site
    {
        if (! $this->userRepository->exists($command->userId)) {
            throw new ModelNotFoundException(sprintf('User with id %s not found', $command->userId));
        }
        $site = $this->siteRepository->findById($command->siteId);
        if (! $site) {
            throw new ModelNotFoundException(sprintf('Site with id %s not found', $command->siteId));
        }

        $site->domain = new Domain($command->domain);
        $site->is_active = $command->isActive;
        $site->user_id = $command->userId;
        $site->save();

        return $site;
    }
}
