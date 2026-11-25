<?php

namespace App\Modules\Permissions\Domain;

final class Capabilities
{
    public const CATALOG = [
        ['read', 'Competition'], ['import', 'Competition'],
        ['read', 'Team'], ['read', 'Player'],
        ['read', 'User'], ['create', 'User'], ['update', 'User'],
        ['status', 'User'], ['assign', 'UserRole'],
        ['read', 'Role'], ['create', 'Role'], ['update', 'Role'],
        ['read', 'Permission'], ['assign', 'RolePermission'],
    ];
}
