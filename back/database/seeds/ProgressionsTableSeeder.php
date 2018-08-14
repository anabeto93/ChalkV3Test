<?php

use App\Progression;
use Illuminate\Database\Seeder;

class ProgressionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Progression::create([
            'session_id' => 1,
            'user_id' => 1
        ]);
    }
}
