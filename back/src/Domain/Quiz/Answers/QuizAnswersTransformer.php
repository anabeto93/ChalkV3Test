<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Quiz\Answers;

use App\Domain\Quiz\Answers\Views\QuestionView;
use App\Domain\Quiz\Answers\Views\QuizAnswerView;

class QuizAnswersTransformer
{
    const QUESTION_SEPARATOR = ';';
    const ANSWER_SEPARATOR = ',';

    /**
     * @param string $answers
     *
     * @return QuizAnswerView
     */
    public function transform(string $answers): QuizAnswerView
    {
        $questionViews = [];
        $questions = explode(self::QUESTION_SEPARATOR, $answers);

        foreach ($questions as $questionAnswers) {
            if ('' === trim($questionAnswers)) {
                $answerIndexes = [];
            } else {
                $answerIndexes = explode(self::ANSWER_SEPARATOR, $questionAnswers);
            }

            $questionViews[] = new QuestionView($answerIndexes);
        }

        return new QuizAnswerView($questionViews);
    }
}
