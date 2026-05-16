<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'web'],
            ['name' => 'Super Admin', 'guard_name' => 'web']
        );

        $user = User::where('email', 'admin@terang.com')->first();

        if ($user) {
            $user->assignRole('Super Admin');
        }
    }
}
