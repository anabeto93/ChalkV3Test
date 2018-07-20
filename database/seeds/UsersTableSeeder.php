<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'institution_id' => 1,
            'first_name' => 'Tester',
            'last_name' => 'Account',
            'phone_numer' =>  '+233123456789',
            'country' => 'gh',
            'locale' => 'en',
            'token' => 'tester'
        ]);

        User::create([
            'institution_id' => 2,
            'first_name' => 'Second',
            'last_name' => 'Account',
            'phone_numer' =>  '+233000000000',
            'country' => 'gh',
            'locale' => 'en',
            'token' => 'logger'
        ]);
    }
}
