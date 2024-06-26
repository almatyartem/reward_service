<?php

namespace App\Services;


use App\Contracts\CacheServiceContract;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceContract
{
    public function get(string $key)
    {
        return Cache::get($key);
    }

    public function set(string $key, $value, int $ttl = null)
    {
        return Cache::set($key, $value, $ttl);
    }

    public function invalidate(string $key)
    {
        return Cache::forget($key);
    }
}
