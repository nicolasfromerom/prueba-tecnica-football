<?php

namespace App\Modules\Teams\Infrastructure;

use App\Modules\Competitions\Infrastructure\Competition;
use App\Modules\Players\Infrastructure\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Team extends Model
{
    protected $fillable = ['external_id', 'name', 'short_name', 'crest'];

    public function competitions(): BelongsToMany
    {
        return $this->belongsToMany(Competition::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
