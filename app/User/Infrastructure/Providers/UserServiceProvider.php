<?php

namespace App\User\Infrastructure\Providers;

use App\User\Domain\Repositories\UserRepositoryInterface;
use App\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
