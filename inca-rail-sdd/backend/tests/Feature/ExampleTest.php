<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Competitions\Domain\FootballProvider;
use App\Modules\Competitions\Domain\ProviderFailure;
use App\Modules\Permissions\Infrastructure\Permission;
use App\Modules\Roles\Infrastructure\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
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
}
