<?php

use App\File;
use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::create([
            'institution_id' => 1,
            'fileable_type' => 'App\Session',
            'fileable_id' => 1,
            'path' => 'dummyfile.png',
            'size' => 2048
        ]);

        File::create([
            'institution_id' => 1,
            'fileable_type' => 'App\Session',
            'fileable_id' => 1,
            'path' => 'seconddummy.jpg',
            'size' => 4096
        ]);
    }
}
