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

        DB::table('cohort_user')->insert([
            'cohort_id' => $cohort->id,
            'user_id' => 1
        ]);

        DB::table('cohort_course')->insert([
            'cohort_id' => $cohort->id,
            'course_id' => 1
        ]);
    }
}
