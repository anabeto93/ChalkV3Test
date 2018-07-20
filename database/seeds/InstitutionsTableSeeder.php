<?php

use App\Institution;
use Illuminate\Database\Seeder;

class InstitutionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Institution::create([
            'name' => 'Chalkboard Education'
        ]);

        Institution::create([
            'name' => 'Other Institution'
        ]);
    }
}
