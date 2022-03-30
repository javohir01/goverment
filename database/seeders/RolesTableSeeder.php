<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => '1','name' => 'admin'],
            ['id' => '2','name' => 'reg_gov'],
            ['id' => '3','name' => 'dist_gov'],

        ];
        DB::table('roles')->insert($roles);
    }
}
