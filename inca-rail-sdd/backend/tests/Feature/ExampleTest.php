<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Competitions\Domain\FootballProvider;
use App\Modules\Competitions\Domain\ProviderFailure;
use App\Modules\Permissions\Infrastructure\Permission;
use App\Modules\Roles\Infrastructure\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Modules\Competitions\Infrastructure\FootballDataClient;
use Illuminate\Support\Facades\Http;
use App\Modules\Competitions\Application\CompetitionCache;
use App\Modules\Competitions\Application\ImportCompetition;
use App\Modules\Competitions\Infrastructure\Competition;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_health_responds_and_api_requires_authentication(): void
    {
        $this->get('/up')->assertStatus(200);
        $this->getJson('/me')->assertStatus(401)->assertJsonPath('error.code', 'UNAUTHENTICATED');
    }

    public function test_upstream_rate_limit_and_failure_use_api_contract(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'importer']);
        $read = Permission::create(['action' => 'read', 'subject' => 'Competition']);
        $import = Permission::create(['action' => 'import', 'subject' => 'Competition']);
        $role->permissions()->attach([$read->id, $import->id]);
        $user->roles()->attach($role);
        $user->refresh();
        Passport::actingAs($user, [], 'api');

        foreach ([429, 502] as $status) {
            app()->instance(FootballProvider::class, new class($status) implements FootballProvider {
                public function __construct(private readonly int $status) {}
                public function competitions(): array { return []; }
                public function competitionTeams(int $competitionId): array { throw new ProviderFailure($this->status, $this->status === 429 ? 42 : null); }
                public function team(int $teamId): array { return []; }
            });
            $response = $this->getJson('/competitions/777');
            $response->assertStatus($status)
                ->assertJsonPath('success', false)
                ->assertJsonPath('error.code', $status === 429 ? 'RATE_LIMITED' : 'UPSTREAM_ERROR');
            if ($status === 429) {
                $response->assertHeader('Retry-After', '42');
            }
        }
    }

    public function test_provider_retry_after_prevents_another_external_call(): void
    {
        config()->set('services.football_data.token', 'fake-provider-token');
        Http::fake(fn () => Http::response([], 429, ['Retry-After' => '27']));
        $client = app(FootballDataClient::class);
        foreach ([111, 222] as $teamId) {
            try {
                $client->team($teamId);
                $this->fail('Expected provider rate limit.');
            } catch (ProviderFailure $failure) {
                $this->assertSame(429, $failure->status);
                $this->assertGreaterThan(0, $failure->retryAfter);
            }
        }
        Http::assertSentCount(1);
    }

    public function test_cache_returns_stale_copy_after_refresh_failure(): void
    {
        $competition = Competition::create(['external_id' => 778, 'name' => 'Saved League', 'synced_at' => now()->subDays(2)]);
        $cache = app(CompetitionCache::class);
        [$copy, $stale] = $cache->resolve(778, fn () => throw new ProviderFailure(503));
        $this->assertSame($competition->id, $copy->id);
        $this->assertTrue($stale);

        $competition->update(['synced_at' => now()]);
        [$copy, $stale] = $cache->resolve(778, fn () => $this->fail('Fresh copy should skip refresh.'));
        $this->assertFalse($stale);
    }

    public function test_partial_provider_failure_does_not_mark_import_complete(): void
    {
        app()->instance(FootballProvider::class, new class implements FootballProvider {
            public function competitions(): array { return []; }
            public function competitionTeams(int $competitionId): array
            {
                return ['competition' => ['id' => 779, 'name' => 'Partial League'], 'teams' => [['id' => 1], ['id' => 2]]];
            }
            public function team(int $teamId): array
            {
                if ($teamId === 2) { throw new ProviderFailure(502); }
                return ['id' => 1, 'name' => 'First Team', 'squad' => []];
            }
        });

        try {
            app(ImportCompetition::class)->execute(779);
            $this->fail('Expected partial provider failure.');
        } catch (ProviderFailure $failure) {
            $this->assertSame(502, $failure->status);
        }
        $this->assertDatabaseMissing('competitions', ['external_id' => 779]);
    }
}
