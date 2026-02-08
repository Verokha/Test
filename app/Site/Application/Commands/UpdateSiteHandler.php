<?php

namespace App\Site\Application\Commands;

use App\Shared\Domain\Types\String\CustomString;
use App\Shared\Domain\Types\String\Domain;
use App\Shared\Domain\Types\String\TelegramChannel;
use App\Site\Domain\Models\Site;
use App\Site\Domain\Repositories\SiteRepositoryInterface;
use App\User\Domain\Models\User;
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
        $site->enabled = $command->enabled;
        $site->user_id = $command->userId;
        $site->block_reason = $command->blockReason ? new CustomString($command->blockReason) : null;
        $site->blocked_at = null;
        if ($command->blocked !== null) {
            $site->blocked = $command->blocked;
            if ($site->blocked) {
                $site->blocked_at = now();
            }
        }
        $site->name = new CustomString($command->name);
        $site->seo = $command->seo;
        $site->telegram_channel = new TelegramChannel($command->telegramChannel);
        $site->save();

        return $site;
    }
}
