<?php

namespace Tests\Feature;

use App\Question;
use App\QuestionAnswer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionAnswerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $question = Question::inRandomOrder()->first();
        $this->post('answers', [
            'title' => 'Test Title',
            'question'  =>  $question->id,
            'correct' => true
        ])->assertJson([
            'error' => false
        ]);
    }

    public function testUpdate()
    {
        $answer = QuestionAnswer::latest('created_at')->first();
        $question = Question::inRandomOrder()->first();
        $this->put('answers/'.$answer->id, [
            'title' => 'The Updated Title',
            'question' => $question->id,
            'correct'   =>  false
        ])->assertJson([
            'error' => false
        ]);
    }

    public function testShow()
    {
        $answer = QuestionAnswer::latest('created_at')->first();
        $this->get('answers/'.$answer->id)
            ->assertJson([
                'title' => 'The Updated Title'
            ]);
    }

    public function testDelete()
    {
        $answer = QuestionAnswer::latest('created_at')->first();
        $this->delete('answers/'.$answer->id)
            ->assertJson([
                'error' => false
            ]);
    }
}
