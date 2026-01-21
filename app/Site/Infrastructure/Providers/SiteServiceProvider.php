<?php

namespace App\Site\Infrastructure\Providers;

use App\Site\Domain\Repositories\SiteRepositoryInterface;
use App\Site\Infrastructure\Repositories\SiteRepository;
use Illuminate\Support\ServiceProvider;

class SiteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SiteRepositoryInterface::class, SiteRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
