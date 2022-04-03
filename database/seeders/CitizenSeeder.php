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
            ['id' => '10','f_name' => 'EShmat','l_name' => 'Toshmatov','m_name' => 'Shermat o`g`li','passport' => 'ad98981','pin' => 'ad989811735d12', 'region_id' => '3','district_id' => '6', 'address' => 'adhjb' ],
            ['id' => '11','f_name' => 'Shermat','l_name' => 'Gishmatov','m_name' => 'Eshmat o`g`li','passport' => 'ad95680','pin' => 'ad98981kl15d12', 'region_id' => '3','district_id' => '6', 'address' => 'adhjb' ],
            ['id' => '12','f_name' => 'Shermat','l_name' => 'Gishmatov','m_name' => 'Eshmat o`g`li','passport' => 'ad12380','pin' => 'ad98981sf15d12', 'region_id' => '3','district_id' => '5', 'address' => 'adhjb' ],
            ['id' => '13','f_name' => 'Shermat','l_name' => 'Gishmatov','m_name' => 'Eshmat o`g`li','passport' => 'ad75380','pin' => 'ad98981fs15d12', 'region_id' => '3','district_id' => '5', 'address' => 'adhjb' ],
            ['id' => '14','f_name' => 'Shermat','l_name' => 'Gishmatov','m_name' => 'Eshmat o`g`li','passport' => 'ad91480','pin' => 'ad9898ffdd5d12', 'region_id' => '1','district_id' => '1', 'address' => 'adhjb' ],
            ['id' => '15','f_name' => 'Shermat','l_name' => 'Gishmatov','m_name' => 'Eshmat o`g`li','passport' => 'ad98580','pin' => 'ad989815715d12', 'region_id' => '1','district_id' => '1', 'address' => 'adhjb' ],

        ];
        DB::table('citizens')->insert($citizens);
    }
}
