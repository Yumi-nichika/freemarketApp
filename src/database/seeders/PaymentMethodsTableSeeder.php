<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'payment_method' => 'コンビニ支払い',
        ];
        DB::table('payment_methods')->insert($param);

        $param = [
            'payment_method' => 'カード支払い',
        ];
        DB::table('payment_methods')->insert($param);
    }
}
