<?php

namespace App\Shared\Presentation;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class ApiResource extends JsonResource
{
    public static $wrap = null;
}
