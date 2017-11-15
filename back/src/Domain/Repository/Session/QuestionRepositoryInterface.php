<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository\Session;

use App\Domain\Model\Session;
use App\Domain\Model\Session\Question;

interface QuestionRepositoryInterface
{
    /**
     * @param Question[] $questions
     */
    public function remove(array $questions);

    /**
     * @param Question $question
     */
    public function add(Question $question);

    /**
     * @param Session $session
     *
     * @return Question[]
     */
    public function getQuestionsOfSession(Session $session): array;
}
