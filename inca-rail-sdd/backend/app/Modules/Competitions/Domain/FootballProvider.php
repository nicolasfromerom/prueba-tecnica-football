<?php

namespace App\Modules\Competitions\Domain;

interface FootballProvider
{
    public function competitions(): array;

    public function competitionTeams(int $competitionId): array;

    public function team(int $teamId): array;
}
