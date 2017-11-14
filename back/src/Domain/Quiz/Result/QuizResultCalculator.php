<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Quiz\Result;

use App\Domain\Model\Session;
use App\Domain\Quiz\Answers\Views\QuestionView;
use App\Domain\Quiz\Answers\Views\QuizAnswerView;
use App\Domain\Quiz\Result\Views\QuizResultView;
use App\Domain\Repository\Session\QuestionRepositoryInterface;

class QuizResultCalculator
{
    /** @var QuestionRepositoryInterface */
    private $questionRepository;

    /**
     * @param QuestionRepositoryInterface $questionRepository
     */
    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @param Session        $session
     * @param QuizAnswerView $quizAnswerView
     *
     * @return QuizResultView
     */
    public function getQuizResultView(Session $session, QuizAnswerView $quizAnswerView): QuizResultView
    {
        $correctQuizAnswerView = $this->getCorrectQuizAnswerView($session);
        $correctAnswersNumber = 0;

        foreach ($quizAnswerView->questionViews as $questionIndex => $questionView) {
            if (isset($correctQuizAnswerView->questionViews[$questionIndex])
                && $correctQuizAnswerView->questionViews[$questionIndex]->answerIndexes === $questionView->answerIndexes
            ) {
                $correctAnswersNumber++;
            }
        }

        return new QuizResultView($correctAnswersNumber, count($correctQuizAnswerView->questionViews));
    }

    /**
     * @param Session $session
     *
     * @return QuizAnswerView
     */
    private function getCorrectQuizAnswerView(Session $session): QuizAnswerView
    {
        $questionViews = [];
        $questions = $this->questionRepository->getQuestionsOfSession($session);

        foreach ($questions as $questionIndex => $question) {
            $answerIndexes = [];

            foreach ($question->getAnswers() as $answerIndex => $answer) {
                if ($answer->isCorrect()) {
                    $answerIndexes[] = $answerIndex;
                }
            }

            $questionViews[] = new QuestionView($answerIndexes);
        }

        return new QuizAnswerView($questionViews);
    }
}
