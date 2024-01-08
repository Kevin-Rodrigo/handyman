<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('users')->updateOrinsert([
            'first_name' => Str::random(10),
            'last_name' => Str::random(10),
            'id' => Str::uuid(),
            'role_id' => 1,
            'email' => 'sup-admin@admin.com',
            'phone' => '+8801759412381',
            'password' => Hash::make('12345678'),
            'is_active' => 1,
            'user_type' => 'super-admin'
        ]);
    }
}
