<?php

namespace App\Modules\Players\Presentation;

use App\Modules\Players\Infrastructure\Player;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;

final class PlayerController
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Player::with('team:id,external_id,name')->orderBy('name')->get());
    }

    public function show(int $player_id): JsonResponse
    {
        return ApiResponse::success(Player::with('team')->where('external_id', $player_id)->firstOrFail());
    }
}
