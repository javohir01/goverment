<?php

namespace Database\Seeders;

use App\Models\Citizen;
use App\Models\District;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create('App\Models\User');
        $districts = District::all();

        foreach ($districts as $district)
        {
            $number1 = mt_rand(5,20);
            for ($int= 0; $int < $number1; $int++){
                $letter = chr(rand(65,90));
                $pass = chr(rand(65,90));
                $pass_number = mt_rand(1000000,9999999);
                $pin =  mt_rand(10000000000000,99999999999999);
                $social = mt_rand(1,18);
                $code = mt_rand(10000,99999);
//                $application_id = mt_rand(0,);
//                $number = '00000';
//                $count = 5;
//                $number = str_replace("0", "$int", $number, $count);
                DB::table('applications')->insert([
                    'f_name' => $faker->firstName,
                    'l_name' => $faker->lastName,
                    'm_name' => $faker->name,
                    'birth_date' => $faker->dateTimeThisCentury->format('Y-m-d'),
                    'region_id' => $district->region_id,
                    'district_id' => $district->id,
                    'address' => $faker->address,
                    'social_id' => $social,
                    'passport' =>$letter.$pass.$pass_number,
                    'pin' => $pin,
                    'code' => $code,
                    'phone_number' => $faker->numerify('+998######'),
                    'number' => str_pad($int+1,6,"0",STR_PAD_LEFT),
                    'status' => mt_rand(0,2),
                ]);
                    if ('status' === 2) {
                        DB::table('applications')->insert([
                            'deny_reason_id' => mt_rand(1,3),
                            'deny_reason_comment' => $faker->randomLetter()
                        ]);
                    }
            }
        }
//        DB::table('citizens')->insert($citizens);
    }
}
