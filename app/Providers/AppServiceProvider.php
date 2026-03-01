<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Contracts\GifProviderInterface;
use App\Services\GiphyService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GifProviderInterface::class, GiphyService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addMinutes(30));
        Passport::personalAccessTokensExpireIn(now()->addMinutes(30));
    }
}
