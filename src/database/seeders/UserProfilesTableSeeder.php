<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
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
        $images = [
            'test_icon.jpg',
        ];

        foreach ($images as $image) {
            $sourcePath = public_path('img/seeder/' . $image);
            $fileName = $image;

            Storage::disk('public')->put(
                'icons/' . $fileName,
                file_get_contents($sourcePath)
            );
        }

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
