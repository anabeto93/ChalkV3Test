<?php

use App\Cohort;
use Illuminate\Database\Seeder;

class CohortsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cohort = Cohort::create([
            'institution_id' => 1,
            'name' => 'A',
        ]);
    }
}
