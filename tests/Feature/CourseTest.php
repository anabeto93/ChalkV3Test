<?php

namespace Tests\Feature;

use App\Course;
use App\Institution;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $institution_id = Institution::find(rand(1,5))->id;

        $this->actingAs(User::first())
            ->post('courses', [
                "name" => "php",
                "teacher" => "APPIER-SIGN",
                "institution" => $institution_id
            ])->assertJson(["error" => false]);
    }

    public function testRemove()
    {
        $course = Course::first();
        $this->actingAs(User::first())
            ->delete('courses/'.$course->id)
            ->assertJson(["error" => false]);
    }
}
