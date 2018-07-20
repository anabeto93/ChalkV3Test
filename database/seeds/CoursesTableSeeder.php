<?php

use App\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $course = Course::create([
            'institution_id' => 1,
            'name' => 'Laravel',
            'description' => 'An intro to V3',
            'teacher' => 'Nii Apa Abbey',
            'enabled' => true
        ]);

        DB::table('course_user')->insert([
            'course_id' => $course->id,
            'user_id' => 1
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
