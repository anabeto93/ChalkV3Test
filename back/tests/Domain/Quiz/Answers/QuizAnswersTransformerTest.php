<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Quiz\Answers;

use App\Domain\Quiz\Answers\QuizAnswersTransformer;
use App\Domain\Quiz\Answers\Views\QuestionView;
use App\Domain\Quiz\Answers\Views\QuizAnswerView;
use PHPUnit\Framework\TestCase;

class QuizAnswersTransformerTest extends TestCase
{
    public function testTransform()
    {
        $quizAnswersTransformer = new QuizAnswersTransformer();
        $quizAnswerView         = $quizAnswersTransformer->transform('0;1,3;1');

        $expectedQuizAnswerView = new QuizAnswerView(
            [
                new QuestionView([0]),
                new QuestionView([1, 3]),
                new QuestionView([1]),
            ]
        );

        $this->assertEquals($expectedQuizAnswerView, $quizAnswerView);
    }
}
