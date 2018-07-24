<?php

namespace Tests\Feature;

use App\Admin;
use Tests\TestCase;

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
        $this->actingAs(Admin::first());
        $this->post('admins/institutions.add', ['institution_id' => rand(1, 5)])->assertJson(['error' => false]);
    }
}
