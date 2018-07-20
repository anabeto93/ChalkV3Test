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
        $this->post('admins', ['username' => 'SIGN', 'password' => 'secret', 'password_confirmation' => 'secret'])->assertJson(['username'=> 'sign']);
    }
}
