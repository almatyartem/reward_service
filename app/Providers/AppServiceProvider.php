<?php

namespace App\Providers;

use App\Contracts\CacheServiceContract;
use App\Contracts\RewardRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Models\Reward;
use App\Observers\RewardObserver;
use App\Services\CacheService;
use App\Services\RewardRepository;
use App\Services\UserServiceAdapter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        RewardRepositoryContract::class => RewardRepository::class,
        UserServiceContract::class => UserServiceAdapter::class,
        CacheServiceContract::class => CacheService::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Reward::observe(RewardObserver::class);
    }
}
