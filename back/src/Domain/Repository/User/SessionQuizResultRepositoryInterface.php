<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository\User;

use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Model\User\SessionQuizResult;

interface SessionQuizResultRepositoryInterface
{
    /**
     * @param User    $user
     * @param Session $session
     *
     * @return SessionQuizResult|null
     */
    public function findByUserAndSession(User $user, Session $session): ?SessionQuizResult;

    /**
     * @param SessionQuizResult $sessionQuizResult
     */
    public function add(SessionQuizResult $sessionQuizResult);
}
