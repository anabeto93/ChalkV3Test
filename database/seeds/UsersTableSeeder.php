<?php

use App\User;
use Illuminate\Database\Seeder;
use Vinkla\Hashids\Facades\Hashids;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'institution_id' => 1,
            'first_name' => 'Tester',
            'last_name' => 'Account',
            'phone_number' =>  '+233123456789',
            'country' => 'gh',
            'locale' => 'en',
        ]);
        $user->token = Hashids::connection('user')->encode($user->id);
        $user->save();

        $user2 = User::create([
            'institution_id' => 2,
            'first_name' => 'Second',
            'last_name' => 'Account',
            'phone_number' =>  '+233000000000',
            'country' => 'gh',
            'locale' => 'en',
        ]);
        $user2->token = Hashids::connection('user')->encode($user2->id);
        $user2->save();
    }
}
