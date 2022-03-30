<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['id' => '0','login' => 'admin1', 'role_id' => '1','email' => '1admin@gmail.com','password' => bcrypt('123456')],
            ['id' => '1','login' => 'admin2', 'role_id' => '1','email' => '2admin@gmail.com','password' => bcrypt('123456')],
            ['id' => '2','login' => 'reg_gov1', 'role_id' => '2','email' => 'reg_gov1@gmail.com','password' => bcrypt('123456')],
            ['id' => '3','login' => 'reg_gov2', 'role_id' => '2','email' => 'reg_gov2@gmail.com','password' => bcrypt('123456')],
            ['id' => '4','login' => 'dist_gov1', 'role_id' => '3','email' => 'dist_gov1@gmail.com','password' => bcrypt('123456')],
            ['id' => '5','login' => 'dist_gov2', 'role_id' => '3','email' => 'dist_gov2@gmail.com','password' => bcrypt('123456')],
        ];
        DB::table('users')->insert($users);
    }
}
