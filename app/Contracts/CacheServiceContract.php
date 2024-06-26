<?php

namespace App\Contracts;

interface CacheServiceContract
{
    public function get(string $key);

    public function set(string $key, $value, int $ttl = null);

    public function invalidate(string $key);
}
