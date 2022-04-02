<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitizenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $citizens = [
            ['id' => '1','f_name' => 'Qoraqalpog‘iston','l_name' => 'Каракалпакстан','m_name' => 'Republic','passport' => 'ad98981','pin' => 'ad989811735d12', 'region_id' => '3','district_id' => '6', 'address' => 'adhjb' ],
            ['id' => '2','f_name' => 'Respublikasi','l_name' => 'Каракалпакстан','m_name' => 'Republic','passport' => 'ad98980','pin' => 'ad989811715d12', 'region_id' => '3','district_id' => '6', 'address' => 'adhjb' ],         ];
        DB::table('citizens')->insert($citizens);
    }
}
