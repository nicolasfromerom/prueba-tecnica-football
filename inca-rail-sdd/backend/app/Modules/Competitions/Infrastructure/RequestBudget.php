<?php

namespace App\Modules\Competitions\Infrastructure;

use App\Modules\Competitions\Domain\ProviderFailure;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;

final class RequestBudget
{
    public function reserve(string $token): void
    {
        $key = 'football-budget:'.hash('sha256', $token);
        try {
            Cache::lock($key.':lock', 10)->block(3, function () use ($key) {
                $now = microtime(true);
                $cooldown = (float) Cache::get($key.':cooldown', 0);
                if ($cooldown > $now) {
                    throw new ProviderFailure(429, (int) ceil($cooldown - $now));
                }
                $attempts = array_values(array_filter(Cache::get($key, []), fn ($time) => $time > $now - 60));
                if (count($attempts) >= 10) {
                    throw new ProviderFailure(429, (int) ceil($attempts[0] + 60 - $now));
                }
                $attempts[] = $now;
                Cache::put($key, $attempts, 61);
            });
        } catch (LockTimeoutException) {
            throw new ProviderFailure(503);
        }
    }

    public function coolDown(string $token, int $seconds): void
    {
        $key = 'football-budget:'.hash('sha256', $token).':cooldown';
        Cache::put($key, microtime(true) + max(1, $seconds), max(1, $seconds));
    }
}
