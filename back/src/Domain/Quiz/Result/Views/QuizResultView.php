<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Quiz\Result\Views;

class QuizResultView
{
    /** @var int */
    public $correctAnswersNumber;

    /** @var int */
    public $questionsNumber;

    /**
     * @param int $correctAnswersNumber
     * @param int $questionsNumber
     */
    public function __construct(int $correctAnswersNumber, int $questionsNumber)
    {
        $this->correctAnswersNumber = $correctAnswersNumber;
        $this->questionsNumber      = $questionsNumber;
    }
}
