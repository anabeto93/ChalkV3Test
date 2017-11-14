<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Quiz\Answers\Views;

class QuestionView
{
    /** @var int[] */
    public $answerIndexes = [];

    /**
     * @param mixed[] $answerIndexes
     */
    public function __construct(array $answerIndexes)
    {
        $answerIndexes = array_map(function ($answerIndex) {
            return (int) $answerIndex;
        }, $answerIndexes);

        $this->answerIndexes = $answerIndexes;
    }
}
