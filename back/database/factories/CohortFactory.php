<?php

use Faker\Generator as Faker;

$factory->define(App\Cohort::class, function (Faker $faker) {
    return [
        "name"  =>  $faker->streetName,
        "institution_id"    =>  \Illuminate\Support\Facades\DB::table('institutions')->inRandomOrder()->first()->id
    ];
});
