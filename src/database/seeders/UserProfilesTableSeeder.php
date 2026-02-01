<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'post_code' => '100-0000',
            'address' => '東京都',
            'building' => 'マンション101',
        ];
        DB::table('user_profiles')->insert($param);

        $param = [
            'user_id' => 2,
            'post_code' => '100-0001',
            'address' => '東京都',
        ];
        DB::table('user_profiles')->insert($param);
    }
}
