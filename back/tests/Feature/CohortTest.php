<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CohortTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $user = Auth::loginUsingId(User::first()->id);
        $this->actingAs($user)
            ->post('cohorts', ['name' => 'php developers'])
            ->assertJson(['error' => false, 'reason' => 'success']);
    }

    public function testUpdate()
    {
        $user = Auth::loginUsingId(User::first()->id);
        $this->actingAs($user)
            ->put('cohorts/6', ['name' => 'laravel Developers'])
            ->assertJson(['error' => false, 'reason' => 'success', 'code' => 200]);
    }
}
