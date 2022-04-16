<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DenyReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deny_reasons = [
            ['id' => '1','name' => 'Фуқаро бошқа ҳудудга тегишли'],
            ['id' => '2','name' => ',Малумотлар хато киритилган'],
            ['id' => '3','name' => 'Фуқаро аввал ёрдам олган'],

        ];
        DB::table('deny_reasons')->insert($deny_reasons);
    }
}
