<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository\Session;

use App\Domain\Model\Session\Answer;

interface AnswerRepositoryInterface
{
    /**
     * @param Answer $answer
     */
    public function add(Answer $answer);
}
