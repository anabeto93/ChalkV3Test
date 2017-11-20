<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User\Quiz;

use App\Application\Command\Command;
use App\Domain\Model\User;

class AnswerSessionQuiz implements Command
{
    /** @var User */
    public $user;

    /** @var string */
    public $sessionUuid;

    /** @var string */
    public $answers;

    /** @var string */
    public $medium;

    /**
     * @param User   $user
     * @param string $sessionUuid
     * @param string $answers
     * @param string $medium
     */
    public function __construct(User $user, string $sessionUuid, string $answers, string $medium)
    {
        $this->user = $user;
        $this->sessionUuid = $sessionUuid;
        $this->answers = $answers;
        $this->medium = $medium;
    }
}
