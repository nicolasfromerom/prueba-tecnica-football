<?php

namespace App\Modules\Players\Infrastructure;

use App\Modules\Teams\Infrastructure\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Player extends Model
{
    protected $fillable = ['external_id', 'team_id', 'name', 'position', 'shirt_number', 'nationality', 'date_of_birth'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
