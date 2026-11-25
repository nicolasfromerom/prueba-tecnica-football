<?php

namespace App\Modules\Teams\Presentation;

use App\Modules\Teams\Infrastructure\Team;
use App\Shared\Presentation\ApiResponse;
use Illuminate\Http\JsonResponse;

final class TeamController
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Team::with('competitions:id,external_id,name')->orderBy('name')->get());
    }
}
