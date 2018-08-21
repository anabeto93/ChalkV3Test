<?php

use App\Question;
use App\QuestionAnswer;
use App\Quiz;
use Illuminate\Database\Seeder;

class QuizzesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create quiz
        $quiz = Quiz::create([
            'institution_id' => 1,
            'quizzable_type' => 'App\Session',
            'quizzable_id' => 1,
            'title' => 'Premier Quiz',
            'description' => 'Le first quiz of V3',
            'enabled' => true
        ]);

        //Question 1
        $question = Question::create([
            'quiz_id' => $quiz->id,
            'type' => 'multiple_choice',
            'title' => 'Which of this is an OOP?',
            'feedback' => 'Well done. OOP stands for Object Oriented Programming language and you found the correct one.'
        ]);
        //Possible Answers
        QuestionAnswer::create([
            'question_id' => $question->id,
            'title' => 'HTML',
        ]);
        QuestionAnswer::create([
            'question_id' => $question->id,
            'title' => 'JAVA',
            'correct' => true
        ]);
        QuestionAnswer::create([
            'question_id' => $question->id,
            'title' => 'Markdown',
        ]);

        //Question 2
        $question2 = Question::create([
            'quiz_id' => $quiz->id,
            'type' => 'short_exact',
            'title' => '2 + 2 - 1',
            'feedback' => 'Well done. OOP stands for Object Oriented Programming language and you found the correct one.'
        ]);
        //Possible Answers

        QuestionAnswer::create([
            'question_id' => $question2->id,
            'title' => '3',
            'correct' => true
        ]);
    }
}
