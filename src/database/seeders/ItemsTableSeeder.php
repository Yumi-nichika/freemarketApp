<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            'item1.jpg',
            'item2.jpg',
            'item3.jpg',
            'item4.jpg',
            'item5.jpg',
            'item6.jpg',
            'item7.jpg',
            'item8.jpg',
            'item9.jpg',
            'item10.jpg',
        ];

        foreach ($images as $image) {
            $sourcePath = public_path('img/seeder/' . $image);
            $fileName = $image;

            Storage::disk('public')->put(
                'items/' . $fileName,
                file_get_contents($sourcePath)
            );
        }

        $param = [
            'user_id' => 1,
            'item_name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'condition_id' => 1,
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'item_path' => 'items/item1.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'item_name' => 'HDD',
            'brand_name' => '西芝',
            'price' => 5000,
            'condition_id' => 2,
            'detail' => '高速で信頼性の高いハードディスク',
            'item_path' => 'items/item2.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'item_name' => '玉ねぎ3束',
            'brand_name' => 'なし',
            'price' => 300,
            'condition_id' => 3,
            'detail' => '新鮮な玉ねぎ3束のセット',
            'item_path' => 'items/item3.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'item_name' => '革靴',
            'brand_name' => '',
            'price' => 4000,
            'condition_id' => 4,
            'detail' => 'クラシックなデザインの革靴',
            'item_path' => 'items/item4.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'item_name' => 'ノートPC',
            'brand_name' => '',
            'price' => 45000,
            'condition_id' => 1,
            'detail' => '高性能なノートパソコン',
            'item_path' => 'items/item5.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 2,
            'item_name' => 'マイク',
            'brand_name' => 'なし',
            'price' => 8000,
            'condition_id' => 2,
            'detail' => '高音質のレコーディング用マイク',
            'item_path' => 'items/item6.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 2,
            'item_name' => 'ショルダーバッグ',
            'brand_name' => '',
            'price' => 3500,
            'condition_id' => 3,
            'detail' => 'おしゃれなショルダーバッグ',
            'item_path' => 'items/item7.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 2,
            'item_name' => 'タンブラー',
            'brand_name' => 'なし',
            'price' => 500,
            'condition_id' => 4,
            'detail' => '使いやすいタンブラー',
            'item_path' => 'items/item8.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 2,
            'item_name' => 'コーヒーミル',
            'brand_name' => 'Starbacks',
            'price' => 4000,
            'condition_id' => 1,
            'detail' => '手動のコーヒーミル',
            'item_path' => 'items/item9.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 2,
            'item_name' => 'メイクセット',
            'brand_name' => '',
            'price' => 2500,
            'condition_id' => 2,
            'detail' => '便利なメイクアップセット',
            'item_path' => 'items/item10.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('items')->insert($param);
    }
}
