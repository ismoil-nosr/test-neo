<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Admin';
        $user->phone = '998901111111';
        $user->password = Hash::make('secret');
        $user->email = 'admin@example.com';
        $user->email_verified_at = now();
        $user->assignRole('admin');
        $user->save();

        $user = new User();
        $user->name = 'Moderator';
        $user->phone = '998902222222';
        $user->password = Hash::make('secret');
        $user->email = 'moderator@example.com';
        $user->email_verified_at = now();
        $user->assignRole('moderator');
        $user->save();

        $user = new User();
        $user->name = 'Viewer';
        $user->phone = '998901234567';
        $user->password = Hash::make('secret');
        $user->email = 'viewer@example.com';
        $user->email_verified_at = now();
        $user->assignRole(UserRoleEnum::VIEWER);
        $user->save();
    }
}
