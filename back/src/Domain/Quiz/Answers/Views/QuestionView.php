<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Quiz\Answers\Views;

use App\Domain\Quiz\Answers\Exception\AnswerIndexMustBeIntegerException;

class QuestionView
{
    /** @var int[] */
    public $answerIndexes = [];

    /**
     * @param mixed[] $answerIndexes
     *
     * @throws AnswerIndexMustBeIntegerException
     */
    public function __construct(array $answerIndexes)
    {
        $answerIndexes = array_map(function ($answerIndex) {
            if (!is_numeric($answerIndex)) {
                throw new AnswerIndexMustBeIntegerException();
            }

            return (int) $answerIndex;
        }, $answerIndexes);

        $this->answerIndexes = $answerIndexes;
    }
}
