<?php

namespace App\Modules\Competitions\Domain;

use RuntimeException;

final class ProviderFailure extends RuntimeException
{
    public function __construct(public readonly int $status, public readonly ?int $retryAfter = null)
    {
        parent::__construct('Football provider unavailable.');
    }
}
