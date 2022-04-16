<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['id' => '1','name' => 'Янги'],
            ['id' => '2','name' => 'Тасдиқланган'],
            ['id' => '3','name' => 'Рад етилган'],

        ];
        DB::table('statuses')->insert($statuses);
    }
}
