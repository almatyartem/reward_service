<?php

namespace App\Observers;

use App\Contracts\CacheServiceContract;
use App\Models\Reward;
use Illuminate\Support\Facades\Log;

class RewardObserver
{
    public function __construct(
        readonly CacheServiceContract $cacheService
    ){}

    /**
     * Handle the Reward "updated" event.
     */
    public function updated(Reward $reward): void
    {
        Log::info('!!!!!!!!!!');
        $this->cacheService->invalidate(Reward::cacheKey($reward->id));
    }

    /**
     * Handle the Reward "deleted" event.
     */
    public function deleted(Reward $reward): void
    {
        $this->cacheService->invalidate(Reward::cacheKey($reward->id));
    }
}
