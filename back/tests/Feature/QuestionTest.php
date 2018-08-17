<?php

namespace Tests\Feature;

use App\Question;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $this->post('questions', [
            "title" => "question title",
            "feedback" => "question feedback",
            "quiz" => 1,
            "type"  => "multiple_choice"
        ])->assertJson([
            "error" =>  false
        ]);
    }

    public function testUpdate()
    {
        $question = Question::inRandomOrder()->first();
        $this->put('questions/'.$question->id, [
            "title" => "Update Question Title",
            "feedback" => "Updated Question Feedback",
            "type"  =>  "short_exact",
            "quiz"  =>  $question->quiz_id
        ])->assertJson([
            "reason" => "Question updated!"
        ]);
    }

    public function testRemove()
    {
        $question = Question::inRandomOrder()->first();
        $this->delete('questions/'.$question->id)->assertJson([
            "reason" => "Question removed"
        ]);
    }
}
