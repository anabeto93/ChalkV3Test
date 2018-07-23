<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $this->post('admins/register', ['username' => 'sign', 'password' => 'secret', 'password_confirmation' => 'secret'])->assertJson(['username'=> 'sign']);

    }

    public function testAddInstitution()
    {
        $this->get('admins/1/institutions/2')->assertJson(['error' => false]);
    }

//    public function testChangePassword()
//    {
//        $this->put('admins/1/change.password', ['password' => 'secret', 'new_password' => 'password', 'new_password_confirmation' => 'password'])->assertJson(['username' => 'sign']);
//    }
}
