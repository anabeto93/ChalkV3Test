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
        $questions = $this->questionRepository->getQuestionsOfSession($session);



        return new QuizResultView(0, 0);
    }
}
