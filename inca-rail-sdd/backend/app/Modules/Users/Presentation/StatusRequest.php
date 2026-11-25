<?php

namespace App\Modules\Users\Presentation;

use App\Shared\Presentation\ApiRequest;

final class StatusRequest extends ApiRequest
{
    public function rules(): array
    {
        return ['is_active' => ['required', 'boolean']];
    }
}
