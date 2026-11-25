<?php

namespace App\Modules\Competitions\Application;

use App\Modules\Competitions\Domain\ProviderFailure;
use App\Modules\Competitions\Infrastructure\Competition;
use Closure;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;

final class CompetitionCache
{
    public function resolve(int $externalId, Closure $refresh): array
    {
        $copy = Competition::where('external_id', $externalId)->whereNotNull('synced_at')->first();
        if ($copy && $copy->synced_at->diffInSeconds(now()) < config('services.football_data.ttl')) {
            return [$copy, false];
        }

        try {
            return Cache::lock('competition-sync:'.$externalId, 120)->block(3, function () use ($externalId, $refresh) {
                $copy = Competition::where('external_id', $externalId)->whereNotNull('synced_at')->first();
                if ($copy && $copy->synced_at->diffInSeconds(now()) < config('services.football_data.ttl')) {
                    return [$copy, false];
                }
                try {
                    return [$refresh(), false];
                } catch (ProviderFailure $failure) {
                    if ($copy) {
                        return [$copy, true];
                    }
                    throw $failure;
                }
            });
        } catch (LockTimeoutException) {
            if ($copy) {
                return [$copy, true];
            }
            throw new ProviderFailure(503);
        }
    }
}
