<?php

use App\Folder;
use Illuminate\Database\Seeder;

class FoldersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Folder::create([
            'course_id' => 1,
            'name' => 'One'
        ]);

        Folder::create([
            'course_id' => 1,
            'order' => 1,
            'name' => 'Two'
        ]);
    }
}
