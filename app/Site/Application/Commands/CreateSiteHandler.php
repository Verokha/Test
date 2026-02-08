<?php

namespace App\Site\Application\Commands;

use App\Shared\Domain\Types\String\CustomString;
use App\Shared\Domain\Types\String\Domain;
use App\Shared\Domain\Types\String\TelegramChannel;
use App\Site\Domain\Models\Site;
use App\User\Domain\Models\User;
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
