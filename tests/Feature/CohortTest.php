<?php

namespace Tests\Feature;

use App\Cohort;
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
        $user = User::first();
        $this->actingAs($user)
            ->post('cohorts', ['name' => 'php developers'])
            ->assertJson(['error' => false, 'reason' => 'Cohort created!']);
    }

    public function testUpdate()
    {
        $user = User::first();
        $cohort = Cohort::inRandomOrder()->first();
        $this->actingAs($user)
            ->put('cohorts/'. $cohort->id, ['name' => 'laravel Developers'])
            ->assertJson(['error' => false, 'reason' => 'Cohort updated!', 'code' => 200]);
    }
}
