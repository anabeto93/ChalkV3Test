<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Manager;

use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Model\User\SessionQuizResult;
use App\Domain\Repository\User\SessionQuizResultRepositoryInterface;

class SessionQuizResultManager
{
    /** @var SessionQuizResultRepositoryInterface */
    private $sessionQuizResultRepository;

    /**
     * @param SessionQuizResultRepositoryInterface $sessionQuizResultRepository
     */
    public function __construct(SessionQuizResultRepositoryInterface $sessionQuizResultRepository)
    {
        $this->sessionQuizResultRepository = $sessionQuizResultRepository;
    }

    /**
     * @param User    $user
     * @param Session $session
     *
     * @return SessionQuizResult|null
     */
    public function findByUserAndSession(User $user, Session $session): ?SessionQuizResult
    {
        return $this->sessionQuizResultRepository->findByUserAndSession($user, $session);
    }
}
