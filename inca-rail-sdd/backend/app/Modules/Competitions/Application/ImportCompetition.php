<?php

namespace App\Modules\Competitions\Application;

use App\Modules\Competitions\Domain\FootballProvider;
use App\Modules\Competitions\Domain\ProviderFailure;
use App\Modules\Competitions\Infrastructure\Competition;
use App\Modules\Players\Infrastructure\Player;
use App\Modules\Teams\Infrastructure\Team;
use Illuminate\Support\Facades\DB;

final class ImportCompetition
{
    public function __construct(private readonly FootballProvider $provider)
    {
    }

    public function execute(int $externalId): Competition
    {
        $listing = $this->provider->competitionTeams($externalId);
        if (!isset($listing['competition']['id'], $listing['competition']['name']) || !isset($listing['teams']) || !is_array($listing['teams'])) {
            throw new ProviderFailure(502);
        }
        $teams = [];
        foreach ($listing['teams'] as $item) {
            if (!isset($item['id'])) {
                throw new ProviderFailure(502);
            }
            $detail = $this->provider->team((int) $item['id']);
            if (!isset($detail['id'], $detail['name'], $detail['squad']) || !is_array($detail['squad'])) {
                throw new ProviderFailure(502);
            }
            $teams[] = $detail;
        }

        return DB::transaction(function () use ($listing, $teams) {
            $source = $listing['competition'];
            $competition = Competition::updateOrCreate(['external_id' => $source['id']], [
                'name' => $source['name'], 'code' => $source['code'] ?? null, 'emblem' => $source['emblem'] ?? null,
            ]);
            $teamIds = [];
            foreach ($teams as $item) {
                $team = Team::updateOrCreate(['external_id' => $item['id']], [
                    'name' => $item['name'], 'short_name' => $item['shortName'] ?? null, 'crest' => $item['crest'] ?? null,
                ]);
                $teamIds[] = $team->id;
                foreach ($item['squad'] as $person) {
                    if (!isset($person['id'], $person['name'])) {
                        continue;
                    }
                    Player::updateOrCreate(['external_id' => $person['id']], [
                        'team_id' => $team->id,
                        'name' => $person['name'],
                        'position' => $person['position'] ?? null,
                        'shirt_number' => $person['shirtNumber'] ?? null,
                        'nationality' => $person['nationality'] ?? null,
                        'date_of_birth' => $person['dateOfBirth'] ?? null,
                    ]);
                }
            }
            $competition->teams()->sync($teamIds);
            $competition->synced_at = now();
            $competition->save();

            return $competition;
        });
    }
}
