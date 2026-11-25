<?php

namespace App\Modules\Competitions\Infrastructure;

use App\Modules\Competitions\Domain\FootballProvider;
use App\Modules\Competitions\Domain\ProviderFailure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

final class FootballDataClient implements FootballProvider
{
    public function __construct(private readonly RequestBudget $budget)
    {
    }

    public function competitions(): array
    {
        return $this->get('/competitions')['competitions'] ?? [];
    }

    public function competitionTeams(int $competitionId): array
    {
        return $this->get('/competitions/'.$competitionId.'/teams');
    }

    public function team(int $teamId): array
    {
        return $this->get('/teams/'.$teamId);
    }

    private function get(string $path): array
    {
        $token = config('services.football_data.token');
        if (!is_string($token) || $token === '') {
            throw new ProviderFailure(503);
        }
        $cacheKey = 'football-provider-response:'.hash('sha256', $token.':'.$path);
        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $this->budget->reserve($token);

        try {
            $response = Http::baseUrl('https://api.football-data.org/v4')
                ->withHeaders(['X-Auth-Token' => $token])
                ->acceptJson()
                ->timeout(8)
                ->get($path);
        } catch (\Throwable) {
            throw new ProviderFailure(503);
        }

        if ($response->status() === 429) {
            $retryAfter = (int) $response->header('Retry-After', 60);
            $this->budget->coolDown($token, $retryAfter);
            throw new ProviderFailure(429, $retryAfter);
        }
        if (!$response->successful() || !is_array($response->json())) {
            throw new ProviderFailure(502);
        }

        $data = $response->json();
        Cache::put($cacheKey, $data, config('services.football_data.ttl'));

        return $data;
    }
}
