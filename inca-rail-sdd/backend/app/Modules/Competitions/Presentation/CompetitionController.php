<?php

namespace App\Modules\Competitions\Presentation;

use App\Modules\Competitions\Domain\FootballProvider;
use App\Modules\Competitions\Domain\ProviderFailure;
use App\Modules\Competitions\Infrastructure\Competition;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class CompetitionController
{
    public function index(FootballProvider $provider): JsonResponse
    {
        $stale = false;
        if (!Cache::get('competition-catalog-updated')) {
            try {
                Cache::lock('competition-catalog-lock', 30)->block(3, function () use ($provider) {
                    if (Cache::get('competition-catalog-updated')) {
                        return;
                    }
                    $items = $provider->competitions();
                    DB::transaction(function () use ($items) {
                        foreach ($items as $item) {
                            if (!isset($item['id'], $item['name'])) {
                                continue;
                            }
                            Competition::updateOrCreate(['external_id' => $item['id']], [
                                'name' => $item['name'],
                                'code' => $item['code'] ?? null,
                                'emblem' => $item['emblem'] ?? null,
                            ]);
                        }
                    });
                    Cache::put('competition-catalog-updated', true, config('services.football_data.ttl'));
                });
            } catch (ProviderFailure $failure) {
                if (!Competition::exists()) {
                    throw $failure;
                }
                $stale = true;
            } catch (\Illuminate\Contracts\Cache\LockTimeoutException) {
                if (!Competition::exists()) {
                    throw new ProviderFailure(503);
                }
                $stale = true;
            }
        }

        return ApiResponse::success(Competition::orderBy('name')->get(), meta: ['stale' => $stale]);
    }
}
