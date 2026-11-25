<?php

namespace App\Modules\Roles\Application;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use Closure;
use Illuminate\Contracts\Cache\LockTimeoutException;

final class ProtectLastAdmin
{
    public function synchronized(Closure $operation): mixed
    {
        try {
            return Cache::lock('active-admin-safety', 10)->block(3, $operation);
        } catch (LockTimeoutException) {
            abort(503);
        }
    }

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
