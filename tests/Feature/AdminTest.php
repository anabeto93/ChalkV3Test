<?php

namespace Tests\Feature;

use App\Admin;
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
        $this->actingAs(Admin::first());
        $this->get('admins/institutions.add')->assertJson(['error' => false]);
    }
}
