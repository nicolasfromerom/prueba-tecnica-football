<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Permissions\Domain\Capabilities;
use App\Modules\Permissions\Infrastructure\Permission;
use App\Modules\Roles\Infrastructure\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (Capabilities::CATALOG as [$action, $subject]) {
            Permission::firstOrCreate(compact('action', 'subject'));
        }
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $reader = Role::firstOrCreate(['name' => 'consulta']);
        $admin->permissions()->sync(Permission::pluck('id'));
        $reader->permissions()->sync(Permission::where('action', 'read')->whereIn('subject', ['Competition', 'Team', 'Player'])->pluck('id'));

        if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
            $user = User::firstOrCreate(['email' => env('ADMIN_EMAIL')], [
                'name' => 'Administrator',
                'password' => env('ADMIN_PASSWORD'),
            ]);
            $user->roles()->syncWithoutDetaching([$admin->id]);
        }
    }
}
