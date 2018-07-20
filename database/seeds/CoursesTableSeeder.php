<?php

use App\Course;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'institution_id' => 1,
            'name' => 'Laravel',
            'description' => 'An intro to V3',
            'teacher' => 'Nii Apa Abbey',
            'enabled' => true
        ]);

        Course::create([
            'institution_id' => 1,
            'name' => 'Symfony',
            'description' => 'Comparison study',
            'teacher' => 'Nii Apa Abbey',
            'enabled' => true
        ]);

        Course::create([
            'institution_id' => 2,
            'name' => 'Nothing',
            'description' => 'We have no idea',
            'teacher' => 'Solomon',
            'enabled' => true
        ]);
    }
}
