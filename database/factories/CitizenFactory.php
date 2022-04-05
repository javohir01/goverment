<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Citizen;
use Faker\Generator as Faker;

$factory->define(Citizen::class, function (Faker $faker) {
    return [
        'passport' => substr('AA'.$faker->ean8,0,9),
        'f_name' => $faker->firstName,
        'l_name' => $faker->lastName,
        'm_name' => $faker->middleName,
        'birthyear'   => $faker->year,
        'birth_date' => 'integer|nullable',
        'region_id' => 'integer|nullable',
        'district_id' => 'integer|nullable',
        'address'=> $faker->address,
        'password' => 'string|nullable',
        'passport' => 'string|nullable',
        'pin' =>$faker->numberBetween($min = 1, $max = 8),
        'remember_token' => 'string|nullable',
        'created_at' => 'datetime|nullable',
        'updated_at' => 'datetime|nullable',

    ];
});
