<?php

namespace App\Providers;

use App\Modules\Competitions\Domain\FootballProvider;
use App\Modules\Competitions\Infrastructure\FootballDataClient;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FootballProvider::class, FootballDataClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
