<?php

namespace App\Modules\Auth\Application;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class Login
{
    public function execute(string $email, string $password): ?array
    {
        $user = User::where('email', $email)->first();
        if (!$user || !$user->is_active || !Hash::check($password, $user->password)) {
            return null;
        }

        return ['token' => $user->createToken('inca-rail')->accessToken, 'token_type' => 'Bearer', 'user' => $user];
    }
}
