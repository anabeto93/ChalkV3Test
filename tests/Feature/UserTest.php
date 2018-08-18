<?php

namespace Tests\Feature;

use App\Admin;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegister()
    {
//        $this->post('register', [
//            'first_name' => 'Solomon',
//            'last_name' => 'appier-sign',
//            'phone_name' => '0249621938',
//            'country' => 'Ghana',
//            'institution_id' => 1
//        ])->assertJson([
//            'error' => false
//        ]);
    }

    public function testLogin()
    {
        $user = User::inRandomOrder()->first();
        $this->get('login/'. $user->token)->assertSee('home');
    }
}
