<?php

namespace App\Modules\Permissions\Infrastructure;

use App\Modules\Roles\Infrastructure\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Permission extends Model
{
    protected $fillable = ['action', 'subject'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
