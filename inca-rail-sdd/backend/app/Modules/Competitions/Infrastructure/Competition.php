<?php

namespace App\Modules\Competitions\Infrastructure;

use App\Modules\Teams\Infrastructure\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Competition extends Model
{
    protected $fillable = ['external_id', 'name', 'code', 'emblem', 'synced_at'];

    protected function casts(): array
    {
        return ['synced_at' => 'datetime'];
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
