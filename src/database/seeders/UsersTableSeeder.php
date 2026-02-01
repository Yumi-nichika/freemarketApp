<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'テスト太郎',
            'email' => 'tarou@test.com',
            'password' => Hash::make('password'),
        ];
        DB::table('users')->insert($param);

        $param = [
            'name' => 'テスト花子',
            'email' => 'hanako@test.com',
            'password' => Hash::make('password'),
        ];
        DB::table('users')->insert($param);
    }
}
