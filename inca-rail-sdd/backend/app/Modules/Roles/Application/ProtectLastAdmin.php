<?php

namespace App\Modules\Roles\Application;

use App\Models\User;
use Illuminate\Validation\ValidationException;

final class ProtectLastAdmin
{
    public function ensureAnotherActiveAdmin(User $user): void
    {
        if (!$user->is_active || !$user->roles()->where('name', 'admin')->exists()) {
            return;
        }
        $others = User::where('is_active', true)
            ->whereKeyNot($user->id)
            ->whereHas('roles', fn ($query) => $query->where('name', 'admin'))
            ->exists();
        if (!$others) {
            throw ValidationException::withMessages(['admin' => 'The last active administrator cannot be removed.']);
        }
    }
}
