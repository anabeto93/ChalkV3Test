<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Quiz\Answers\Views;

class QuizAnswerView
{
    /** @var QuestionView[] */
    public $questionViews = [];

    /**
     * @param QuestionView[] $questionViews
     */
    public function __construct(array $questionViews)
    {
        $this->questionViews = $questionViews;
    }
}
