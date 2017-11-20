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

    /** @var bool[] */
    public $questionsResult;

    /**
     * @param int    $correctAnswersNumber
     * @param int    $questionsNumber
     * @param bool[] $questionsResult
     */
    public function __construct(int $correctAnswersNumber, int $questionsNumber, array $questionsResult)
    {
        $this->correctAnswersNumber = $correctAnswersNumber;
        $this->questionsNumber      = $questionsNumber;
        $this->questionsResult      = $questionsResult;
    }
}
